<?php namespace Waynelogic\MagicForms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Waynelogic\MagicForms\Form\AbstractForm;
use Waynelogic\MagicForms\Form\CommonForm;
use Waynelogic\MagicForms\Models\FormRecord;
use Waynelogic\MagicForms\Notifications\MagicFormNotification;

abstract class MagicFormsController
{
    public string $formTypeName = 'magic_form_type';
    private array $forms;
    public AbstractForm $currentForm;

    public function __construct()
    {
        $this->forms = $this->getForms();
    }

    public function store(Request $request)
    {
        // Получаем тип формы
        $formType = $request->input($this->formTypeName);

        // Получаем соответствующий тип формы
        $sFormClass = $this->getFormClass($formType);

        // Создаём экземпляр Form и валидируем
        $this->currentForm = $form = new $sFormClass();
        $attributes = $form->attributes();
        $pass = $form->pass();
        $validatedData = $request->validate(rules: $form->rules(), attributes: $attributes);

        // Получаем данные
        $data = $form->onlyValidated ? $validatedData : $request->except($this->formTypeName);
        // Подготовка данных
        $files = [];
        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $files[] = $key;
                unset($data[$key]);
            }
            if (isset($attributes[$key])) {
                $data[$attributes[$key]] = $value;
                unset($data[$key]);
            }
            if (isset($pass[$key])) {
                $data[$pass[$key]] = $value;
                unset($data[$key]);
            }
            // Все остальные данные оставляем в неизменном види
        }

        $obFormRecord = FormRecord::query()->create([
            'group' => $request->group ?? $form->group,
            'form_type' => $formType ?? 'common',
            'ip' => $request->ip(),
            'form_data' => $data,
        ]);

        if ($files) {
            $obFormRecord->addMultipleMediaFromRequest($files)->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('files');
            });
        }

        if ($form->routes()) {
            $this->sendNotifications($obFormRecord);
        }

        return $this->success($request);
    }

    /**
     * Loads all forms to private $forms property
     * @return array
     */
    public function getForms() : array
    {
        return [];
    }
    private function getFormClass(mixed $formType)
    {
        return $this->forms[$formType] ?? CommonForm::class;
    }

    private function sendNotifications(FormRecord $obFormRecord)
    {
        Notification::routes($this->currentForm->routes())->notify(new MagicFormNotification($obFormRecord));
    }
    abstract function success(Request $request);
}
