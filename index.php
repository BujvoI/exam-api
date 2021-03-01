<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: multipart/form-data');
require_once 'DBOperation.php';
$params = json_decode(trim(file_get_contents('php://input')), true);

if(isset($params) and !empty($params)){
    $data = $params;
    $data = $data['data'];
    if(isset($data['type'])){
        $db = new DBOperation();
        switch ($data['type']){
            case 'get':
                echo $db->getProducts($data['id'] !== 0?$data['id']:null);
                break;
            case 'create':
                echo isset($data['data'])?$db->newProduct($data['data']):json_encode(['success'=>false,'error'=>'Param "data" is not recognized.']);
                break;
            case 'update':
                echo (isset($data['data']) and  isset($data['id']))?$db->updateProduct($data['data'],$data['id']):json_encode(['success'=>false,'error'=>'Param "data" or "id" is not recognized.']);
                break;
            case 'updateCount':
                echo (isset($data['id']) and  isset($data['plusminus']))?$db->updateProductCount($data['id'],$data['plusminus']):json_encode(['success'=>false,'error'=>'Param "id" or "plusminus" is not recognized.']);
                break;
            case 'delete':
                echo isset($data['id'])?$db->deleteProduct($data['id']):json_encode(['success'=>false,'error'=>'Param "id" is not recognized.']);
                break;
            default:
                echo json_encode(['success'=>false,'error'=>'Type is not recognized.']);
                break;
        }
    }else{
        echo json_encode(['success'=>false,'error'=>'couldn\'t find the variable "type".']);
    }
}else{
    echo json_encode(['success'=>false,'error'=>'this data transfer method is not supported']);
}