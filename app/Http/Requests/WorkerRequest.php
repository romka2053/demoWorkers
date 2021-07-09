<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

                'name' => ['required', 'string', 'max:100'],
                'post' => ['required', 'string', 'max:40'],
                'device_date' => ['required','date_format:Y-m-d'],
                'salary' => ['required','numeric','max:999999','min:300'],
                'parent_id' => ['nullable','integer','exists:workers,id'],
                'fupload' => ['image'],


        ];
    }


    public function messages()
    {
        return [
            'name.required'=>'Поле ФИО обязательно для заполнения',
            'name.max'=>'Максимальная длина поля 100 символов',
            'post.required'=>'Поле должность обязательно для заполнения',
            'post.max'=>'Максимальная длина поля 40 символов',
            'device_date.required'=>'Поле дата устройства обязательно для заполнения',
            'device_date.date_format'=>'Введите дату в формате Год-Месяц-День',
            'salary.required'=>'Поле зарплата обязательно для заполнения',
            'salary.max'=>'Максимальная зарплата = 999999 руб.',
            'salary.min'=>'Минимальная зарплата = 300 руб.',
            'salary.numeric'=>'Допускается ввод только числовых значений, дробная часть через точку',
            'parent_id.integer'=>'Допускается только целочисленные значения',
            'fupload.image'=>'Загружаемый файл должен быть в формате JPG',
        ];
    }


}
