<?php
class DetailsController{
    public static function verifySizeSelection($data){
        $errors = [];
        if(empty($data['Size'])){
            $errors['errors_size'] = 'Please select a size';
        }
        return $errors;
    }
}
