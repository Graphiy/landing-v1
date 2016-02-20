<?php 

require('protected/config.php');
require('protected/model.php');

if (isset($_POST['question_id']) && (int)$_POST['question_id'] > -1) {
    session_start();
    $data['question_id'] = (int)$_POST['question_id'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $data['answer'] = json_encode(@$_POST['answer']);
    if ($data['question_id'] == 1) {
        session_regenerate_id(true);
    }
    $data['session_id'] = session_id();
    $model = model::get_instance();
    $model->addRow($data);    
}

?>
