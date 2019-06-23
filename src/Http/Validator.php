<?php


namespace Lumos\Lustom\Http;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator as V;
use Illuminate\Http\Request;

abstract class Validator
{
    abstract public function rules();

    public function messages() { return []; }

    public function customAttributes() { return []; }

    public static function validate(Request $request) {
        $_v = new static();
        $v = V::make($request->all(), $_v->rules(), $_v->messages(), $_v->customAttributes());

        if ($v->fails()) { return abort(Response::HTTP_BAD_REQUEST, $v->errors());}
        return $request->all();
    }
}