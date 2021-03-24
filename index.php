 public function questionAddByExcel($slug, Request $request){
        dd('ddd');
        try{

            $test = $this->test->findBySlug($slug);
            if(!empty($test->id)){
                if($request->hasFile('excelFile')){
                    $path = $request->file('excelFile')->getRealPath();
                    $data = Excel::load($path, function($reader) {})->get();
                    if(!empty($data) && $data->count()){
                        $insert = [];
                        // echo '<pre>';
                        // die(print_r($data->toArray()));
                        foreach ($data->toArray() as $key => $value) {

                            if(count($value)){

                                // foreach ($value as $v) {

                                    // $insert[] = $v;
                                    if(!empty($value['question']) AND !empty($value['optiona']) AND !empty($value['optionb']) AND !empty($value['optionc']) AND !empty($value['optiond']) AND !empty($value['answer']))
                                    $insert[] = [
                                        'testId' => $test->id,
                                        'courseId' =>$test->courseId,
                                        'subjectId' =>$test->subjectId,
                                        'question' => $value['question'], 
                                        'optionA' => $value['optiona'],
                                        'optionB' => $value['optionb'],
                                        'optionC' => $value['optionc'],
                                        'optionD' => $value['optiond'],
                                        'answer' => $value['answer'],
                                        'description' => $value['explanation']
                                    ];

                                // }
                            }
                        }
                        // die(print_r($insert));
                        if(count($insert)){
                            $this->question->insert($insert);
                            return redirect()->route('test-question',[$test->id])->withSuccess('Questions have been addedd Successfully.');
                        }
                        else
                        {
                          return redirect()->route('test-question',[$test->id])->withError('No questions found in the excel file');  
                        }
                        
                    }
                    else
                        return redirect()->route('test-question',[$test->id])->withError('Excel File is Empty');
                }
                else
                    return redirect()->route('test-question',[$test->id])->withError('No Excel File uploaded');
            }
            else
            {
                return redirect()->route('test-question',[$test->id])->withError('Invalid Test Identification');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withError('Oops,something wrong !');
        }
        
    }
