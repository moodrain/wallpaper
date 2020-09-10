<?php

if (! function_exists('dj'))
{
    function dj(...$elems)
    {
        $dump = count($elems) == 1 ? $elems[0] : $elems;
        header('Content-Type: application/json');
        echo json_encode($dump);
        exit;
    }
}

if (! function_exists('vd'))
{
    function vd(...$elems)
    {
        $dump = count($elems) == 1 ? $elems[0] : $elems;
        var_dump($dump);
        exit;
    }
}

if (! function_exists('now'))
{
    function now($unix = false) {
        return $unix ? time() : date('Y-m-d H:i:s');
    }
}

if (! function_exists('rs'))
{
    function rs($data = [], $msg = '', $code = 0)
    {
        return response()->json(compact('code', 'msg', 'data'));
    }
}

if (! function_exists('ers'))
{
    function ers($msg = '', $code = 1, $data = [])
    {
        return response()->json(compact('code', 'msg', 'data'));
    }
}

if (! function_exists('user'))
{
    function user()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }
}

if (! function_exists('uid'))
{
    function uid()
    {
        return \Illuminate\Support\Facades\Auth::id();
    }
}

if (! function_exists('expIf'))
{
    function expIf($if, $msg, $code = 1)
    {
        if ($if) {
            throw new Exception($msg, $code);
        }
    }
}

if (! function_exists('bladeIncludeExp'))
{
    function bladeIncludeExp($exp)
    {
        if (!$exp) {
            return [];
        }
        $exps = explode(';', $exp);
        $result = [];
        foreach ($exps as $exp) {
            [$key, $val] = explode(':', $exp);
            $result[$key] = $val;
        }
        return $result;
    }
}

if (! function_exists('mDate'))
{
    function mDate($time = null, $format = 'Y-m-d H:i:s')
    {
        return date($format, $time ?? time());
    }
}

if (! function_exists('ext'))
{
    function ext($path)
    {
        $info = pathinfo($path);
        return empty($info['extension']) ? null : strtolower($info['extension']);
    }
}

if (! function_exists('singleUser'))
{
    function singleUser()
    {
        return config('app.single_user') ? App\Models\User::query()->find(config('app.single_user')) : false;
    }
}

if (! function_exists('bv')) {
    function bv($objOrProp, $default = '')
    {
        static $obj;
        if(is_string($objOrProp)) {
            $prop = $objOrProp;
            $default === null && $default = 'null';
            $return = $default;
            if (! $obj) {
                $return = old($prop) ?? $default;
            }
            if (is_object($obj)) {
                $return = old($prop) ?? $obj->$prop ?? $default;
            } elseif (is_array($obj)) {
                $return = old($prop) ?? $obj[$prop] ?? $default;
            }
            if ($return instanceof \Illuminate\Support\Collection || $return instanceof \Illuminate\Database\Eloquent\Collection) {
                return $return->pluck('id')->all();
            }
            return $return;
        }
        $obj = $objOrProp;
    }
}

if (! function_exists('sendMail'))
{
    function sendMail($to, $subject, $content)
    {
        \Illuminate\Support\Facades\Mail::html($content, function(\Illuminate\Mail\Message $msg) use ($to, $subject) {
            $msg->to($to)->subject($subject);
        });
    }
}

if (! function_exists('startWith'))
{
    function startWith($start, $str)
    {
        return mb_substr($str, 0, 1) == $start ? $str : ($start . $str);
    }
}

if (! function_exists('endWith'))
{
    function endWith($end, $str)
    {
        return mb_substr($str, mb_strlen($str) - 1, 1) == $end ? $str : ($str . $end);
    }
}

if (! function_exists('mobile'))
{
    function mobile()
    {
        return \Illuminate\Support\Str::of(request()->userAgent())->contains(['mobile', 'Mobile']);
    }
}