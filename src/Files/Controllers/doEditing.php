// ------------------------------------------------------------------------------------------------------
public function doEditing($model,$cases){
	$param = \request('param', ''); // column
	$value = \request('value', ''); // new value

	if(isset($cases[$param])){
		$model=call_user_func($cases[$param],$model,$value);
	}else{
		throw  new \ErrorMessageException("این فیلد قایل ویرایش نیست !!!",\StatusCodes::HTTP_NOT_ACCEPTABLE);
	}

	if($model!=null)
		$model->save();

	return sucBack('ویرایش شد');
}

