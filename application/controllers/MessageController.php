<?

namespace controllers;

class MessageController
{
    public $message;
    public $validator;

    public function run()
    {
        $validate_result = $this->request_validation($_GET);

        if ($validate_result) { // Если есть ошибка валидации

            $response = [
                'status' => 'error',
                'error' => $validate_result
            ];

        } else {

            $message = json_encode($_GET);
            $addInQueu = new RabbitController();
            $addInQueu->add_message($message);

            $response = [
                'status' => 'success',
                'message' => 'Заявка отправлена'
            ];
        }

        echo json_encode($response);
        return;

    }

    public function request_validation($get)
    {
        //Тут происходит валидация
        $json_error = [];

        if (empty($get['account'])) {
            $json_error[] = [
                'message' => 'Account',
            ];
        }

        if (!$get['email']) {
            $json_error[] = [
                'message' => 'Email',
            ];
        }

        if (!$get['phone']) {
            $json_error[] = [
                'message' => 'Phone',
            ];
        }

        if (!$get['response_type']) {
            $json_error[] = [
                'message' => 'Response type',
            ];
        }

        if (!$get['date_from']) {
            $json_error[] = [
                'message' => 'Date from',
            ];
        }

        if (!$get['date_to']) {
            $json_error[] = [
                'message' => 'Date to',
            ];
        }

        return $json_error;

    }
}
