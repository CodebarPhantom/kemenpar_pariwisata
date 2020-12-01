<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':Attribute harus diterima.',
    'active_url' => ':Attribute bukan link yang valid.',
    'after' => ':Attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':Attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':Attribute hanya dapat berisi huruf.',
    'alpha_dash' => ':Attribute hanya dapat berisi huruf, angka, tanda hubung (-) dan garis bawah (_).',
    'alpha_num' => ':Attribute hanya dapat berisi huruf dan angka.',
    'array' => ':Attribute harus berupa array.',
    'before' => ':Attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':Attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':Attribute harus antara :min sampai :max.',
        'file' => 'Ukuran :attribute harus antara :min sampai :max kilobyte.',
        'string' => ':Attribute harus antara :min sampai :max karakter.',
        'array' => ':Attribute harus memiliki antara :min sampai :max barang.',
    ],
    'boolean' => ':Attribute harus benar atau salah.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':Attribute tidak valid.',
    'date_equals' => ':Attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => 'Format :attribute harus seperti :format.',
    'different' => ':Attribute dan :other harus berbeda.',
    'digits' => ':Attribute harus :digits digit.',
    'digits_between' => ':Attribute harus antara :min sampai :max digit.',
    'dimensions' => 'Dimensi gambar :attribute tidak valid.',
    'distinct' => ':Attribute memiliki nilai duplikat.',
    'email' => ':Attribute tidak valid.',
    'ends_with' => ':Attribute harus diakhiri dengan salah satu dari berikut ini: :values',
    'exists' => ':Attribute tidak valid.',
    'file' => ':Attribute harus berupa file.',
    'filled' => ':Attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => ':Attribute harus lebih besar dari :value.',
        'file' => 'Ukuran :attribute harus lebih besar dari :value kilobyte.',
        'string' => ':Attribute harus lebih dari :value karakter.',
        'array' => ':Attribute harus memiliki lebih dari :value barang.',
    ],
    'gte' => [
        'numeric' => ':Attribute harus lebih besar dari atau sama dengan :value.',
        'file' => 'Ukuran :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string' => ':Attribute harus lebih dari atau sama dengan :value karakter.',
        'array' => ':Attribute haru memiliki :value barang atau lebih.',
    ],
    'image' => ':Attribute harus berupa gambar.',
    'in' => ':Attribute tidak valid.',
    'in_array' => ':Attribute tidak ada di :other.',
    'integer' => ':Attribute harus berupa bilangan bulat.',
    'ip' => ':Attribute tidak valid.',
    'ipv4' => ':Attribute tidak valid.',
    'ipv6' => ':Attribute tidak valid.',
    'json' => ':Attribute tidak valid.',
    'lt' => [
        'numeric' => ':Attribute harus kurang dari :value.',
        'file' => 'Ukuran :attribute harus kurang dari :value kilobyte.',
        'string' => ':Attribute harus kurang dari :value karakter.',
        'array' => ':Attribute harus memiliki kurang dari :value barang.',
    ],
    'lte' => [
        'numeric' => ':Attribute harus kurang dari atau sama dengan :value.',
        'file' => 'Ukuran :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string' => ':Attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => ':Attribute tidak boleh memiliki lebih dari :value barang.',
    ],
    'max' => [
        'numeric' => ':Attribute tidak boleh lebih dari :max.',
        'file' => 'Ukuran :attribute tidak boleh lebih besar dari :max kilobyte.',
        'string' => ':Attribute tidak boleh lebih dari :max karakter.',
        'array' => ':Attribute tidak boleh memiliki lebih dari :max barang.',
    ],
    'mimes' => 'Tipe file :attribute harus berupa: :values.',
    'mimetypes' => 'Tipe file :attribute harus berupa: :values.',
    'min' => [
        'numeric' => ':Attribute minimal harus :min.',
        'file' => 'Ukuran :attribute minimal harus :min kilobyte.',
        'string' => ':Attribute minimal harus :min karakter.',
        'array' => ':Attribute minimal harus memiliki :min barang.',
    ],
    'not_in' => ':Attribute tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':Attribute harus berupa angka.',
    'present' => ':Attribute harus ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':Attribute wajib diisi.',
    'required_if' => ':Attribute wajib diisi.',
    'required_unless' => ':Attribute wajib diisi.',
    'required_with' => ':Attribute wajib diisi.',
    'required_with_all' => ':Attribute wajib diisi.',
    'required_without' => ':Attribute wajib diisi.',
    'required_without_all' => ':Attribute wajib diisi.',
    'same' => ':Attribute dan :other harus sama.',
    'size' => [
        'numeric' => ':Attribute harus :size.',
        'file' => 'Ukuran :attribute harus :size kilobyte.',
        'string' => ':Attribute harus :size karakter.',
        'array' => ':Attribute harus berisi :size barang.',
    ],
    'starts_with' => ':Attribute harus diawali dengan salah satu dari berikut ini: :values',
    'string' => ':Attribute tidak valid.',
    'timezone' => ':Attribute tidak valid.',
    'unique' => ':Attribute ini sudah terdaftar.',
    'uploaded' => ':Attribute gagal diunggah.',
    'url' => 'Format :attribute tidak valid.',
    'uuid' => ':Attribute tidak valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
