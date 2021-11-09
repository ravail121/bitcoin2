<?php
use App\Models\Etemplate;
use App\Models\GeneralSettings;

if (!function_exists('overwriteEnvFile')) {
    /**
     * Overwrite env keys.
     *
     * @param array $keys
     *
     * @return bool
     *
     * */
    function overwriteEnvFile(array $keys = [])
    {
        if (count($keys) < 1) {
            return false;
        }

        $env = file_get_contents(base_path() . '/.env');
        $env = preg_split('/\s+/', $env);

        foreach ($keys as $key => $value) {
            foreach ($env as $env_key => $env_value) {
                $entry = explode("=", $env_value, 2);
                if ($entry[0] == $key) {
                    $env[$env_key] = $key . "=" . $value;
                } else {
                    $env[$env_key] = $env_value;
                }
            }
        }

        $env = implode("\n", $env);
        file_put_contents(base_path() . '/.env', $env);

        return true;
    }
}

if (! function_exists('send_email')) {

    function send_email( $to, $name, $subject, $message)
    {
        $temp = Etemplate::first();
        $gnl = GeneralSettings::first();
        $template = $temp->emessage;
        $from = $temp->esender;
        $title= 'Bitcoin.ngo';
		if($gnl->email_notification == 1)
		{
//echo $temp->esender;exit;
			$headers = "From: $gnl->title <$from> \r\n";
			$headers .= "Reply-To: $gnl->title <$from> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$mm = str_replace("{{name}}",$name,$template);
			$message = str_replace("{{message}}",$message,$mm);
            $html=$message;
            // mail($to, $subject, $message, $headers);
            Mail::send([],[], function ($message) use ($title,$name,$html,$from,$to,$subject) {
                $message->from($from, $title);
                $message->to($to,$name);
                $message->subject($subject);
                $message->setBody($html, 'text/html'); //html body

            });
		}
    }
}


if (! function_exists('send_sms'))
{

    function send_sms( $to, $message)
    {
        $temp = Etemplate::first();
        $gnl = GeneralSettings::first();
        if($gnl->sms_notification == 1)
        {
            $sendtext = urlencode($message);
            $appi = $temp->smsapi;
            $appi = str_replace("{{number}}",$to,$appi);
            $appi = str_replace("{{message}}",$sendtext,$appi);
            $result = file_get_contents($appi);
        }
    }
}


if (! function_exists('notify'))
{
    function notify( $user, $subject, $message)
    {
        send_email($user->email, $user->username, $subject, $message);
        send_sms($user->mobile, strip_tags($message));
    }
}

function Replace($data) {
    $data = str_replace("'", "", $data);
    $data = str_replace("!", "", $data);
    $data = str_replace("@", "", $data);
    $data = str_replace("#", "", $data);
    $data = str_replace("$", "", $data);
    $data = str_replace("%", "", $data);
    $data = str_replace("^", "", $data);
    $data = str_replace("&", "", $data);
    $data = str_replace("*", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(")", "", $data);
    $data = str_replace("+", "", $data);
    $data = str_replace("=", "", $data);
    $data = str_replace(",", "", $data);
    $data = str_replace(":", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);
    $data = str_replace("?", "", $data);
    $data = str_replace("  ", "_", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace(".", "-", $data);
    $data = strtolower(str_replace("  ", "-", $data));
    $data = strtolower(str_replace(" ", "-", $data));
    $data = strtolower(str_replace(" ", "-", $data));
    $data = strtolower(str_replace("__", "-", $data));
    return str_replace("_", "-", $data);
}
