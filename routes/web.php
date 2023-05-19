<?php

use Illuminate\Support\Facades\Route;
//use app/Http/Controllers/AbduController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('welcome'); });

Route::get('/mail', function () { return view('mail'); });
Route::post('sendmail', 'MailController@sendmail');

//Route::get('/main5', 'MainController@index5');
//Route::get('/main4', 'MainController@index4');
//Route::get('/main3', 'MainController@index3');
//Route::get('/main2', 'MainController@index2');
//Route::get('/main1', 'MainController@index1');
Route::get('/main', 'MainController@index');

Route::post('/main/checklogin', 'MainController@checklogin');
Route::get('main/successlogin', 'MainController@successlogin');
Route::get('main/logout', 'MainController@logout');
Route::post('main/successlogin', 'MainController@store');
Route::get('/errorInformationCheck', 'MainController@showErrorPage');
Route::get('/testDrive','MainController@testDrive');
Route::get('/testeePicture', 'MainController@testeePicture');
Route::post('/submitTesteePicture', 'MainController@submitTesteePicture');
Route::get('main/successfulLogin', 'MainController@successfulLogin');

Route::get('/grade', function () { return view('grade/gradeLogin'); });
Route::post('/grade/checklogin', 'grade\GradeController@checklogin');
Route::post('/grade/searchGradeList', 'grade\GradeListController@search');
Route::get('/answerDownload', function () { return view('answerDownload/answerDownloadLogin'); });
Route::post('/answerDownload/checklogin', 'answerDownload\AnswerDownloadController@checklogin');
Route::post('/answerDownload/searchAnswerDownloadList', 'answerDownload\AnswerDownloadListController@search');

Route::get('/admin', function () { return view('admin/adminLogin'); });
Route::post('/admin/checklogin', 'admin\AdminController@checklogin');
Route::post('/admin/menuPush', 'admin\AdminMenuController@menuPush');
Route::post('/admin/searchExamineeList', 'admin\ExamineeListController@search');
Route::post('/admin/editExaminee', 'admin\ExamineeEditController@init');
Route::post('/admin/registExaminee', 'admin\ExamineeEditController@regist');
Route::post('/admin/pictureExaminee', 'admin\ExamineeEditController@picture');

Route::post('/admin/passwordRegist', 'admin\PasswordRegistController@regist');
Route::get('/admin/adminMenu', function () { return view('admin\adminMenu'); });
Route::post('/admin/sitePasswordRegist', 'admin\SitePasswordController@regist');
Route::get('/supervisor', function () { return view('admin/supervisorLogin'); });
Route::post('/admin/checkLoginSupervisor', 'admin\SupervisorController@checklogin');

Route::get('/admin/supervisorMenu', function () { return view('admin/supervisorMenu'); });

Route::post('/admin/supervisorMenuPush', 'admin\SupervisorMenuController@menuPush');
Route::post('/admin/supervisorPasswordRegist', 'admin\SupervisorPasswordRegistController@regist');
Route::get('/admin/get_city/{country}', 'admin\SupervisorMenuController@get_city');
Route::post('/admin/searchExamProgress', 'admin\ExamProgressController@search');
Route::post('/admin/registExamProgress', 'admin\ExamProgressController@regist');
Route::post('/admin/pictureExamProgress', 'admin\ExamProgressController@picture');

Route::get('/excelinfo/{city}', 'admin\AdminController@excelinfo');
////////////////////////////

Route::get('/example','Q5\Vocabulary\Q5S1Q2Controller@example');

Route::get('/Q5VocabularyQ1', 'Q5\Vocabulary\Q5S1Q1Controller@showQuestion');
Route::get('Q5VocabularyQ1/fetchData', 'Q5\Vocabulary\Q5S1Q1Controller@fetchData');
Route::post('/saveChoiceRequestQ5S1Q1','Q5\Vocabulary\Q5S1Q1Controller@saveChoiceRequestPost');
Route::post('/Q5VocabularyQ1SubmitData', 'Q5\Vocabulary\Q5S1Q1Controller@getResultToCalculate');

Route::get('/Q5VocabularyQ2', 'Q5\Vocabulary\Q5S1Q2Controller@showQuestion');
Route::get('Q5VocabularyQ2/fetchData', 'Q5\Vocabulary\Q5S1Q2Controller@fetchData');
Route::post('/saveChoiceRequestQ5S1Q2', 'Q5\Vocabulary\Q5S1Q2Controller@saveChoiceRequestPost');
Route::post('/Q5VocabularyQ2SubmitData', 'Q5\Vocabulary\Q5S1Q2Controller@getResultToCalculate');

Route::get('/Q5VocabularyQ3', 'Q5\Vocabulary\Q5S1Q3Controller@showQuestion');
Route::get('Q5VocabularyQ3/fetchData', 'Q5\Vocabulary\Q5S1Q3Controller@fetchData');
Route::post('/saveChoiceRequestQ5S1Q3', 'Q5\Vocabulary\Q5S1Q3Controller@saveChoiceRequestPost');
Route::post('/Q5VocabularyQ3SubmitData', 'Q5\Vocabulary\Q5S1Q3Controller@getResultToCalculate');

Route::get('/Q5VocabularyQ3Picture', 'Q5\Vocabulary\Q5S1Q3PictureController@showQuestion');

Route::get('/testAPI', 'AdminController@updateGoogleSheet');

Route::get('/Q5VocabularyQ4', 'Q5\Vocabulary\Q5S1Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S1Q4','Q5\Vocabulary\Q5S1Q4Controller@saveChoiceRequestPost');
Route::post('/Q5VocabularyQ4SubmitData', 'Q5\Vocabulary\Q5S1Q4Controller@getResultToCalculate');

Route::post('/timeOutQ5S1', 'Q5\Vocabulary\Q5S1Controller@timeOutCalculation');

Route::get('/EoP1','Q5\Vocabulary\Q5S1Q4Controller@endVocabularyQ5');

Route::get('/Q5VocabularyWelcome', 'MainController@vocabulary5Q');

Route::get('/Q5ReadingWelcome', 'MainController@reading5Q');

Route::get('/Q5ReadingQ1', 'Q5\Reading\Q5S2Q1Controller@showQuestion');
Route::get('/Q5ReadingQ1/fetchData', 'Q5\Reading\Q5S2Q1Controller@fetchData');
Route::post('/saveChoiceRequestQ5S2Q1', 'Q5\Reading\Q5S2Q1Controller@saveChoiceRequestPost');
Route::post('/Q5ReadingQ1SubmitData', 'Q5\Reading\Q5S2Q1Controller@getResultToCalculate');

Route::get('/Q5ReadingQ2', 'Q5\Reading\Q5S2Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S2Q2', 'Q5\Reading\Q5S2Q2Controller@saveChoiceRequestPost');
Route::post('/Q5ReadingQ2SubmitData', 'Q5\Reading\Q5S2Q2Controller@getResultToCalculate');

Route::get('/Q5ReadingQ3', 'Q5\Reading\Q5S2Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S2Q3', 'Q5\Reading\Q5S2Q3Controller@saveChoiceRequestPost');
Route::post('/Q5ReadingQ3SubmitData', 'Q5\Reading\Q5S2Q3Controller@getResultToCalculate');

Route::get('/Q5ReadingQ4', 'Q5\Reading\Q5S2Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S2Q4', 'Q5\Reading\Q5S2Q4Controller@saveChoiceRequestPost');
Route::post('/Q5ReadingQ4SubmitData', 'Q5\Reading\Q5S2Q4Controller@getResultToCalculate');

Route::get('/Q5ReadingQ5', 'Q5\Reading\Q5S2Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S2Q5', 'Q5\Reading\Q5S2Q5Controller@saveChoiceRequestPost');
Route::post('/Q5ReadingQ5SubmitData', 'Q5\Reading\Q5S2Q5Controller@getResultToCalculate');

Route::get('/Q5ListeningTest', 'testController@showQuestion');

Route::get('/Q5ReadingQ6', 'Q5\Reading\Q5S2Q6Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S2Q6', 'Q5\Reading\Q5S2Q6Controller@saveChoiceRequestPost');
Route::post('/Q5ReadingQ6SubmitData', 'Q5\Reading\Q5S2Q6Controller@getResultToCalculate');

Route::post('/timeOutQ5S2', 'Q5\Reading\Q5S2Controller@timeOutCalculation');

Route::get('/Q5S3Start', function () { return view('Q5\Listening\Q5S3Start'); });
Route::get('/Q5S3VolumeTest', function () { return view('Q5\Listening\Q5S3VolumeTest'); });

Route::get( '/Q5ListeningQ1N1',           'Q5\Listening\Q5S3Q1N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q1N1', 'Q5\Listening\Q5S3Q1N1Controller@saveChoiceRequestPost');
Route::get( '/Q5ListeningQ1N4',           'Q5\Listening\Q5S3Q1N4Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q1N4', 'Q5\Listening\Q5S3Q1N4Controller@saveChoiceRequestPost');
Route::get( '/Q5ListeningQ1N6',           'Q5\Listening\Q5S3Q1N6Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q1N6', 'Q5\Listening\Q5S3Q1N6Controller@saveChoiceRequestPost');
Route::post('/Q5ListeningQ1SubmitData',   'Q5\Listening\Q5S3Q1N1Controller@getResultToCalculate');

Route::get( '/Q5ListeningQ2N1',           'Q5\Listening\Q5S3Q2N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q2N1', 'Q5\Listening\Q5S3Q2N1Controller@saveChoiceRequestPost');
Route::get( '/Q5ListeningQ2N5',           'Q5\Listening\Q5S3Q2N5Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q2N5', 'Q5\Listening\Q5S3Q2N5Controller@saveChoiceRequestPost');
Route::post('/Q5ListeningQ2SubmitData',   'Q5\Listening\Q5S3Q2N1Controller@getResultToCalculate');

Route::get( '/Q5ListeningQ3N1',           'Q5\Listening\Q5S3Q3N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q3N1', 'Q5\Listening\Q5S3Q3N1Controller@saveChoiceRequestPost');
Route::get( '/Q5ListeningQ3N2',           'Q5\Listening\Q5S3Q3N2Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q3N2', 'Q5\Listening\Q5S3Q3N2Controller@saveChoiceRequestPost');
Route::get( '/Q5ListeningQ3N3',           'Q5\Listening\Q5S3Q3N3Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q3N3', 'Q5\Listening\Q5S3Q3N3Controller@saveChoiceRequestPost');
Route::post('/Q5ListeningQ3SubmitData',   'Q5\Listening\Q5S3Q3N1Controller@getResultToCalculate');

Route::get( '/Q5ListeningQ4',           'Q5\Listening\Q5S3Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ5S3Q4', 'Q5\Listening\Q5S3Q4Controller@saveChoiceRequestPost');
Route::post('/Q5ListeningQ4SubmitData',   'Q5\Listening\Q5S3Q4Controller@getResultToCalculate');

Route::get('/Q5TestResult',   'Q5\Q5ResultController@radarChartResult');

Route::get('/ScoreDetailOption',  'Q5\ScoreDetailOptionController@show');
Route::post('/saveChoiceRequestTestResultQ5', 'Q5\ScoreDetailOptionController@saveChoiceRequestgrade');
Route::post('/Q5gradecertificatechoiceSubmitData',   'Q5\ScoreDetailOptionController@addGradeOption');
 
Route::get('/Gradehomepage', function () { return view('Q5\Gradehomepage'); });

Route::get('/End5Level', function () {
    return view('Q5\End5Level');
});

Route::get('/Q4VocabularyQ1', 'Q4\Vocabulary\Q4S1Q1Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S1Q1','Q4\Vocabulary\Q4S1Q1Controller@saveChoiceRequestPost');
Route::post('/Q4VocabularyQ1SubmitData', 'Q4\Vocabulary\Q4S1Q1Controller@getResultToCalculate');
Route::get('Q4VocabularyQ1/fetchData', 'Q4\Vocabulary\Q4S1Q1Controller@fetchData');

Route::get('/Q4VocabularyQ2', 'Q4\Vocabulary\Q4S1Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S1Q2','Q4\Vocabulary\Q4S1Q2Controller@saveChoiceRequestPost');
Route::post('/Q4VocabularyQ2SubmitData', 'Q4\Vocabulary\Q4S1Q2Controller@getResultToCalculate');

Route::get('/Q4VocabularyQ3', 'Q4\Vocabulary\Q4S1Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S1Q3','Q4\Vocabulary\Q4S1Q3Controller@saveChoiceRequestPost');
Route::post('/Q4VocabularyQ3SubmitData', 'Q4\Vocabulary\Q4S1Q3Controller@getResultToCalculate');
Route::get('Q4VocabularyQ3/fetchData', 'Q4\Vocabulary\Q4S1Q3Controller@fetchData');

Route::get('/Q4VocabularyQ4', 'Q4\Vocabulary\Q4S1Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S1Q4','Q4\Vocabulary\Q4S1Q4Controller@saveChoiceRequestPost');
Route::post('/Q4VocabularyQ4SubmitData', 'Q4\Vocabulary\Q4S1Q4Controller@getResultToCalculate');

Route::get('/Q4VocabularyQ5', 'Q4\Vocabulary\Q4S1Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S1Q5','Q4\Vocabulary\Q4S1Q5Controller@saveChoiceRequestPost');
Route::post('/Q4VocabularyQ5SubmitData', 'Q4\Vocabulary\Q4S1Q5Controller@getResultToCalculate');

Route::post('/timeOutQ4S1', 'Q4\Vocabulary\Q4S1Controller@timeOutCalculation');
Route::get('/Q4VocabularyWelcome', 'MainController@vocabulary4Q');
Route::get('/Q4ReadingWelcome', 'MainController@reading4Q');

Route::get('/Q4ReadingQ1', 'Q4\Reading\Q4S2Q1Controller@showQuestion');
Route::get('Q4ReadingQ1/fetchData', 'Q4\Reading\Q4S2Q1Controller@fetchData');
Route::post('/saveChoiceRequestQ4S2Q1', 'Q4\Reading\Q4S2Q1Controller@saveChoiceRequestPost');
Route::post('/Q4ReadingQ1SubmitData', 'Q4\Reading\Q4S2Q1Controller@getResultToCalculate');

Route::get('/Q4ReadingQ2', 'Q4\Reading\Q4S2Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S2Q2', 'Q4\Reading\Q4S2Q2Controller@saveChoiceRequestPost');
Route::post('/Q4ReadingQ2SubmitData', 'Q4\Reading\Q4S2Q2Controller@getResultToCalculate');

Route::get('/Q4ReadingQ3', 'Q4\Reading\Q4S2Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S2Q3', 'Q4\Reading\Q4S2Q3Controller@saveChoiceRequestPost');
Route::post('/Q4ReadingQ3SubmitData', 'Q4\Reading\Q4S2Q3Controller@getResultToCalculate');

Route::get('/Q4ReadingQ4', 'Q4\Reading\Q4S2Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S2Q4', 'Q4\Reading\Q4S2Q4Controller@saveChoiceRequestPost');
Route::post('/Q4ReadingQ4SubmitData', 'Q4\Reading\Q4S2Q4Controller@getResultToCalculate');

Route::get('/Q4ReadingQ5', 'Q4\Reading\Q4S2Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S2Q5', 'Q4\Reading\Q4S2Q5Controller@saveChoiceRequestPost');
Route::post('/Q4ReadingQ5SubmitData', 'Q4\Reading\Q4S2Q5Controller@getResultToCalculate');

Route::get('/Q4ReadingQ6', 'Q4\Reading\Q4S2Q6Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S2Q6', 'Q4\Reading\Q4S2Q6Controller@saveChoiceRequestPost');
Route::post('/Q4ReadingQ6SubmitData', 'Q4\Reading\Q4S2Q6Controller@getResultToCalculate');

Route::post('/timeOutQ4S2', 'Q4\Reading\Q4S2Controller@timeOutCalculation');

Route::get('/Q4S3Start', function () { return view('Q4\Listening\Q4S3Start'); });
Route::get('/Q4S3VolumeTest', function () { return view('Q4\Listening\Q4S3VolumeTest'); }); 
Route::get( '/Q4ListeningQ1N1',           'Q4\Listening\Q4S3Q1N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q1N1', 'Q4\Listening\Q4S3Q1N1Controller@saveChoiceRequestPost');
Route::get( '/Q4ListeningQ1N4',           'Q4\Listening\Q4S3Q1N4Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q1N4', 'Q4\Listening\Q4S3Q1N4Controller@saveChoiceRequestPost');
Route::get( '/Q4ListeningQ1N6',           'Q4\Listening\Q4S3Q1N6Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q1N6', 'Q4\Listening\Q4S3Q1N6Controller@saveChoiceRequestPost');
Route::get( '/Q4ListeningQ1N8',           'Q4\Listening\Q4S3Q1N8Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q1N8', 'Q4\Listening\Q4S3Q1N8Controller@saveChoiceRequestPost');
Route::post('/Q4ListeningQ1SubmitData',   'Q4\Listening\Q4S3Q1N1Controller@getResultToCalculate');

Route::get( '/Q4ListeningQ2N1',           'Q4\Listening\Q4S3Q2N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q2N1', 'Q4\Listening\Q4S3Q2N1Controller@saveChoiceRequestPost');
Route::get( '/Q4ListeningQ2N5',           'Q4\Listening\Q4S3Q2N5Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q2N5', 'Q4\Listening\Q4S3Q2N5Controller@saveChoiceRequestPost');
Route::post('/Q4ListeningQ2SubmitData',   'Q4\Listening\Q4S3Q2N1Controller@getResultToCalculate');

Route::get( '/Q4ListeningQ3N1',           'Q4\Listening\Q4S3Q3N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q3N1', 'Q4\Listening\Q4S3Q3N1Controller@saveChoiceRequestPost');
Route::get( '/Q4ListeningQ3N2',           'Q4\Listening\Q4S3Q3N2Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q3N2', 'Q4\Listening\Q4S3Q3N2Controller@saveChoiceRequestPost');
Route::get( '/Q4ListeningQ3N3',           'Q4\Listening\Q4S3Q3N3Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q3N3', 'Q4\Listening\Q4S3Q3N3Controller@saveChoiceRequestPost');
Route::post('/Q4ListeningQ3SubmitData',   'Q4\Listening\Q4S3Q3N1Controller@getResultToCalculate');

Route::get( '/Q4ListeningQ4',             'Q4\Listening\Q4S3Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ4S3Q4',   'Q4\Listening\Q4S3Q4Controller@saveChoiceRequestPost');
Route::post('/Q4ListeningQ4SubmitData',   'Q4\Listening\Q4S3Q4Controller@getResultToCalculate');

Route::get('/Q4TestResult',   'Q4\Q4ResultController@radarChartResult');

Route::get('/ScoreDetailOption4',  'Q4\ScoreDetailOptionController@show');
Route::post('/saveChoiceRequestTestResultQ4', 'Q4\ScoreDetailOptionController@saveChoiceRequestgrade');
Route::post('/Q4gradecertificatechoiceSubmitData',   'Q4\ScoreDetailOptionController@addGradeOption');

Route::get('/Gradehomepage4', function () { return view('Q4\Gradehomepage'); });

Route::get('/End4Level', function () {
    return view('Q4\End4Level');
});

Route::get('/Q3VocabularyWelcome', 'MainController@vocabulary3Q');

Route::get('/Q3VocabularyQ1', 'Q3\Vocabulary\Q3S1Q1Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S1Q1','Q3\Vocabulary\Q3S1Q1Controller@saveChoiceRequestPost');
Route::post('/Q3VocabularyQ1SubmitData', 'Q3\Vocabulary\Q3S1Q1Controller@getResultToCalculate');
Route::get('Q3VocabularyQ1/fetchData', 'Q3\Vocabulary\Q3S1Q1Controller@fetchData');

Route::get('/Q3VocabularyQ2', 'Q3\Vocabulary\Q3S1Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S1Q2','Q3\Vocabulary\Q3S1Q2Controller@saveChoiceRequestPost');
Route::post('/Q3VocabularyQ2SubmitData', 'Q3\Vocabulary\Q3S1Q2Controller@getResultToCalculate');
Route::get('Q3VocabularyQ2/fetchData', 'Q3\Vocabulary\Q3S1Q2Controller@fetchData');

Route::get('/Q3VocabularyQ3', 'Q3\Vocabulary\Q3S1Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S1Q3','Q3\Vocabulary\Q3S1Q3Controller@saveChoiceRequestPost');
Route::post('/Q3VocabularyQ3SubmitData', 'Q3\Vocabulary\Q3S1Q3Controller@getResultToCalculate');
Route::get('Q3VocabularyQ3/fetchData', 'Q3\Vocabulary\Q3S1Q3Controller@fetchData');

Route::get('/Q3VocabularyQ4', 'Q3\Vocabulary\Q3S1Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S1Q4','Q3\Vocabulary\Q3S1Q4Controller@saveChoiceRequestPost');
Route::post('/Q3VocabularyQ4SubmitData', 'Q3\Vocabulary\Q3S1Q4Controller@getResultToCalculate');
Route::get('Q3VocabularyQ4/fetchData', 'Q3\Vocabulary\Q3S1Q4Controller@fetchData');

Route::get('/Q3VocabularyQ5', 'Q3\Vocabulary\Q3S1Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S1Q5','Q3\Vocabulary\Q3S1Q5Controller@saveChoiceRequestPost');
Route::post('/Q3VocabularyQ5SubmitData', 'Q3\Vocabulary\Q3S1Q5Controller@getResultToCalculate');
Route::get('Q3VocabularyQ5/fetchData', 'Q3\Vocabulary\Q3S1Q5Controller@fetchData');

Route::post('/timeOutQ3S1', 'Q3\Vocabulary\Q3S1Controller@timeOutCalculation');

Route::get('/Q3ReadingWelcome', 'MainController@reading3Q');

Route::get('/Q3ReadingQ1', 'Q3\Reading\Q3S2Q1Controller@showQuestion');
Route::get('Q3ReadingQ1/fetchData', 'Q3\Reading\Q3S2Q1Controller@fetchData');
Route::post('/saveChoiceRequestQ3S2Q1', 'Q3\Reading\Q3S2Q1Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ1SubmitData', 'Q3\Reading\Q3S2Q1Controller@getResultToCalculate');

Route::get('/Q3ReadingQ2', 'Q3\Reading\Q3S2Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S2Q2', 'Q3\Reading\Q3S2Q2Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ2SubmitData', 'Q3\Reading\Q3S2Q2Controller@getResultToCalculate');
Route::get('Q3ReadingQ2/fetchData', 'Q3\Reading\Q3S2Q2Controller@fetchData');

Route::get('/Q3ReadingQ3', 'Q3\Reading\Q3S2Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S2Q3', 'Q3\Reading\Q3S2Q3Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ3SubmitData', 'Q3\Reading\Q3S2Q3Controller@getResultToCalculate');

Route::get('/Q3ReadingQ4', 'Q3\Reading\Q3S2Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S2Q4', 'Q3\Reading\Q3S2Q4Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ4SubmitData', 'Q3\Reading\Q3S2Q4Controller@getResultToCalculate');
Route::get('/Q3ReadingQ4/fetchData', 'Q3\Reading\Q3S2Q4Controller@fetchData');

Route::get('/Q3ReadingQ5', 'Q3\Reading\Q3S2Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S2Q5', 'Q3\Reading\Q3S2Q5Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ5SubmitData', 'Q3\Reading\Q3S2Q5Controller@getResultToCalculate');
Route::get('/Q3ReadingQ5/fetchData', 'Q3\Reading\Q3S2Q5Controller@fetchData');

Route::get('/Q3ReadingQ6', 'Q3\Reading\Q3S2Q6Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S2Q6', 'Q3\Reading\Q3S2Q6Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ6SubmitData', 'Q3\Reading\Q3S2Q6Controller@getResultToCalculate');

Route::get('/Q3ReadingQ7', 'Q3\Reading\Q3S2Q7Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S2Q7', 'Q3\Reading\Q3S2Q7Controller@saveChoiceRequestPost');
Route::post('/Q3ReadingQ7SubmitData', 'Q3\Reading\Q3S2Q7Controller@getResultToCalculate');

Route::post('/timeOutQ3S2', 'Q3\Reading\Q3S2Controller@timeOutCalculation');


Route::get('/Q3TestResult',   'Q3\Q3ResultController@radarChartResult');

Route::get('/ScoreDetailOption3',  'Q3\ScoreDetailOptionController@show');
Route::post('/saveChoiceRequestTestResultQ3', 'Q3\ScoreDetailOptionController@saveChoiceRequestgrade');
Route::post('/Q3gradecertificatechoiceSubmitData',   'Q3\ScoreDetailOptionController@addGradeOption');

Route::get('/Gradehomepage3', function () { return view('Q3\Gradehomepage'); });

Route::get('/End3Level', function () { return view('Q3\End3Level'); });

Route::get('/resetEverything', 'MainController@returnToMainAfterExam');

Route::get('/Q5ReadingQ6Picture', 'Q5\Reading\Q5S2Q6ShowAllController@showQuestion');

Route::get('/Q3S3Start', function () { return view('Q3\Listening\Q3S3Start'); });
Route::get('/Q3S3VolumeTest', function () { return view('Q3\Listening\Q3S3VolumeTest'); });
Route::get( '/Q3ListeningQ1N1',           'Q3\Listening\Q3S3Q1N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q1N1', 'Q3\Listening\Q3S3Q1N1Controller@saveChoiceRequestPost');
Route::get( '/Q3ListeningQ1N4',           'Q3\Listening\Q3S3Q1N4Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q1N4', 'Q3\Listening\Q3S3Q1N4Controller@saveChoiceRequestPost');
Route::get( '/Q3ListeningQ1N6',           'Q3\Listening\Q3S3Q1N6Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q1N6', 'Q3\Listening\Q3S3Q1N6Controller@saveChoiceRequestPost');
Route::post('/Q3ListeningQ1SubmitData',   'Q3\Listening\Q3S3Q1N1Controller@getResultToCalculate');

Route::get( '/Q3ListeningQ2N1',           'Q3\Listening\Q3S3Q2N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q2N1', 'Q3\Listening\Q3S3Q2N1Controller@saveChoiceRequestPost');
Route::get( '/Q3ListeningQ2N5',           'Q3\Listening\Q3S3Q2N5Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q2N5', 'Q3\Listening\Q3S3Q2N5Controller@saveChoiceRequestPost');
Route::post('/Q3ListeningQ2SubmitData',   'Q3\Listening\Q3S3Q2N1Controller@getResultToCalculate');

Route::get( '/Q3ListeningQ5',             'Q3\Listening\Q3S3Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q5',   'Q3\Listening\Q3S3Q5Controller@saveChoiceRequestPost');
Route::post('/Q3ListeningQ5SubmitData',   'Q3\Listening\Q3S3Q5Controller@getResultToCalculate');
  
Route::get( '/Q3ListeningQ3',             'Q3\Listening\Q3S3Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q3',   'Q3\Listening\Q3S3Q3Controller@saveChoiceRequestPost');
Route::post('/Q3ListeningQ3SubmitData',   'Q3\Listening\Q3S3Q3Controller@getResultToCalculate');

Route::get( '/Q3ListeningQ4N1',           'Q3\Listening\Q3S3Q4N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q4N1', 'Q3\Listening\Q3S3Q4N1Controller@saveChoiceRequestPost');
Route::get( '/Q3ListeningQ4N2',           'Q3\Listening\Q3S3Q4N2Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q4N2', 'Q3\Listening\Q3S3Q4N2Controller@saveChoiceRequestPost');
Route::get( '/Q3ListeningQ4N3',           'Q3\Listening\Q3S3Q4N3Controller@showQuestion');
Route::post('/saveChoiceRequestQ3S3Q4N3', 'Q3\Listening\Q3S3Q4N3Controller@saveChoiceRequestPost');
Route::post('/Q3ListeningQ4SubmitData',   'Q3\Listening\Q3S3Q4N1Controller@getResultToCalculate');

Route::get('/Q2S3Start', function () { return view('Q2\Listening\Q2S3Start'); });
Route::get('/Q2S3VolumeTest', function () { return view('Q2\Listening\Q2S3VolumeTest'); });
Route::get( '/Q2ListeningQ1',             'Q2\Listening\Q2S3Q1Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q1',   'Q2\Listening\Q2S3Q1Controller@saveChoiceRequestPost');
Route::post('/Q2ListeningQ1SubmitData',   'Q2\Listening\Q2S3Q1Controller@getResultToCalculate');
Route::get( '/Q2ListeningQ2N1',           'Q2\Listening\Q2S3Q2N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q2N1', 'Q2\Listening\Q2S3Q2N1Controller@saveChoiceRequestPost');
Route::get( '/Q2ListeningQ2N5',           'Q2\Listening\Q2S3Q2N5Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q2N5', 'Q2\Listening\Q2S3Q2N5Controller@saveChoiceRequestPost');
Route::post('/Q2ListeningQ2SubmitData',   'Q2\Listening\Q2S3Q2N1Controller@getResultToCalculate');
Route::get( '/Q2ListeningQ3',             'Q2\Listening\Q2S3Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q3',   'Q2\Listening\Q2S3Q3Controller@saveChoiceRequestPost');
Route::post('/Q2ListeningQ3SubmitData',   'Q2\Listening\Q2S3Q3Controller@getResultToCalculate');
Route::get( '/Q2ListeningQ4N1',           'Q2\Listening\Q2S3Q4N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q4N1', 'Q2\Listening\Q2S3Q4N1Controller@saveChoiceRequestPost');
Route::get( '/Q2ListeningQ4N2',           'Q2\Listening\Q2S3Q4N2Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q4N2', 'Q2\Listening\Q2S3Q4N2Controller@saveChoiceRequestPost');
Route::post('/Q2ListeningQ4SubmitData',   'Q2\Listening\Q2S3Q4N1Controller@getResultToCalculate');
Route::get( '/Q2ListeningQ5',             'Q2\Listening\Q2S3Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S3Q5',   'Q2\Listening\Q2S3Q5Controller@saveChoiceRequestPost');
Route::post('/Q2ListeningQ5SubmitData',   'Q2\Listening\Q2S3Q5Controller@getResultToCalculate');

Route::get('/Q2TestResult',   'Q2\Q2ResultController@radarChartResult');

Route::get('/ScoreDetailOption2',  'Q2\ScoreDetailOptionController@show');
Route::post('/saveChoiceRequestTestResultQ2', 'Q2\ScoreDetailOptionController@saveChoiceRequestgrade');
Route::post('/Q2gradecertificatechoiceSubmitData',   'Q2\ScoreDetailOptionController@addGradeOption');

Route::get('/Gradehomepage2', function () { return view('Q2\Gradehomepage'); });

Route::get('/End2Level', function () { return view('Q2\End2Level'); });
Route::get('/Q2VocabularyWelcome', 'MainController@vocabulary2Q');

Route::get('/Q2VocabularyQ1', 'Q2\Vocabulary\Q2S1Q1Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q1','Q2\Vocabulary\Q2S1Q1Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ1SubmitData', 'Q2\Vocabulary\Q2S1Q1Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ2', 'Q2\Vocabulary\Q2S1Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q2','Q2\Vocabulary\Q2S1Q2Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ2SubmitData', 'Q2\Vocabulary\Q2S1Q2Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ3', 'Q2\Vocabulary\Q2S1Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q3','Q2\Vocabulary\Q2S1Q3Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ3SubmitData', 'Q2\Vocabulary\Q2S1Q3Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ4', 'Q2\Vocabulary\Q2S1Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q4','Q2\Vocabulary\Q2S1Q4Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ4SubmitData', 'Q2\Vocabulary\Q2S1Q4Controller@getResultToCalculate');
Route::get('/Q2VocabularyQ4/fetchData', 'Q2\Vocabulary\Q2S1Q4Controller@fetchData' );

Route::get('/Q2VocabularyQ5', 'Q2\Vocabulary\Q2S1Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q5','Q2\Vocabulary\Q2S1Q5Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ5SubmitData', 'Q2\Vocabulary\Q2S1Q5Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ6', 'Q2\Vocabulary\Q2S1Q6Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q6','Q2\Vocabulary\Q2S1Q6Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ6SubmitData', 'Q2\Vocabulary\Q2S1Q6Controller@getResultToCalculate');


Route::get('/Q2VocabularyQ7', 'Q2\Vocabulary\Q2S1Q7Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q7','Q2\Vocabulary\Q2S1Q7Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ7SubmitData', 'Q2\Vocabulary\Q2S1Q7Controller@getResultToCalculate');
Route::get('/Q2VocabularyQ7/fetchData', 'Q2\Vocabulary\Q2S1Q7Controller@fetchData');

Route::get('/Q2VocabularyQ8', 'Q2\Vocabulary\Q2S1Q8Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q8','Q2\Vocabulary\Q2S1Q8Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ8SubmitData', 'Q2\Vocabulary\Q2S1Q8Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ9', 'Q2\Vocabulary\Q2S1Q9Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q9','Q2\Vocabulary\Q2S1Q9Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ9SubmitData', 'Q2\Vocabulary\Q2S1Q9Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ10', 'Q2\Vocabulary\Q2S1Q10Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q10','Q2\Vocabulary\Q2S1Q10Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ10SubmitData', 'Q2\Vocabulary\Q2S1Q10Controller@getResultToCalculate');
Route::get('Q2VocabularyQ10/fetchData', 'Q2\Vocabulary\Q2S1Q10Controller@fetchData');


Route::get('/Q2VocabularyQ14', 'Q2\Vocabulary\Q2S1Q14Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q14','Q2\Vocabulary\Q2S1Q14Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ14SubmitData', 'Q2\Vocabulary\Q2S1Q14Controller@getResultToCalculate');


Route::get('/Q2VocabularyQ11', 'Q2\Vocabulary\Q2S1Q11Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q11','Q2\Vocabulary\Q2S1Q11Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ11SubmitData', 'Q2\Vocabulary\Q2S1Q11Controller@getResultToCalculate');
Route::get('Q2VocabularyQ11/fetchData', 'Q2\Vocabulary\Q2S1Q11Controller@fetchData');

Route::get('/Q2VocabularyQ12', 'Q2\Vocabulary\Q2S1Q12Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q12','Q2\Vocabulary\Q2S1Q12Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ12SubmitData', 'Q2\Vocabulary\Q2S1Q12Controller@getResultToCalculate');

Route::get('/Q2VocabularyQ13', 'Q2\Vocabulary\Q2S1Q13Controller@showQuestion');
Route::post('/saveChoiceRequestQ2S1Q13','Q2\Vocabulary\Q2S1Q13Controller@saveChoiceRequestPost');
Route::post('/Q2VocabularyQ13SubmitData', 'Q2\Vocabulary\Q2S1Q13Controller@getResultToCalculate');

Route::post('/timeOutQ2S1', 'Q2\Vocabulary\Q2S1Controller@timeOutCalculation');

Route::get('/Q1VocabularyWelcome', 'MainController@vocabulary1Q');

Route::get('/Q1VocabularyQ1', 'Q1\Vocabulary\Q1S1Q1Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q1','Q1\Vocabulary\Q1S1Q1Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ1SubmitData', 'Q1\Vocabulary\Q1S1Q1Controller@getResultToCalculate');
Route::get('Q1VocabularyQ1/fetchData', 'Q1\Vocabulary\Q1S1Q1Controller@fetchData');


Route::get('/Q1S3Start', function () { return view('Q1\Listening\Q1S3Start'); });
Route::get('/Q1S3VolumeTest', function () { return view('Q1\Listening\Q1S3VolumeTest'); });
Route::get( '/Q1ListeningQ1',             'Q1\Listening\Q1S3Q1Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q1',   'Q1\Listening\Q1S3Q1Controller@saveChoiceRequestPost');
Route::post('/Q1ListeningQ1SubmitData',   'Q1\Listening\Q1S3Q1Controller@getResultToCalculate');
Route::get( '/Q1ListeningQ2N1',           'Q1\Listening\Q1S3Q2N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q2N1', 'Q1\Listening\Q1S3Q2N1Controller@saveChoiceRequestPost');
Route::get( '/Q1ListeningQ2N5',           'Q1\Listening\Q1S3Q2N5Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q2N5', 'Q1\Listening\Q1S3Q2N5Controller@saveChoiceRequestPost');
Route::post('/Q1ListeningQ2SubmitData',   'Q1\Listening\Q1S3Q2N1Controller@getResultToCalculate');
Route::get( '/Q1ListeningQ3',             'Q1\Listening\Q1S3Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q3',   'Q1\Listening\Q1S3Q3Controller@saveChoiceRequestPost');
Route::post('/Q1ListeningQ3SubmitData',   'Q1\Listening\Q1S3Q3Controller@getResultToCalculate');

Route::get( '/Q1ListeningQ4',             'Q1\Listening\Q1S3Q4N1Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q4N1', 'Q1\Listening\Q1S3Q4N1Controller@saveChoiceRequestPost');
Route::get( '/Q1ListeningQ4N2',           'Q1\Listening\Q1S3Q4N2Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q4N2', 'Q1\Listening\Q1S3Q4N2Controller@saveChoiceRequestPost');
Route::post('/Q1ListeningQ4SubmitData',   'Q1\Listening\Q1S3Q4N1Controller@getResultToCalculate');
Route::get( '/Q1ListeningQ5',             'Q1\Listening\Q1S3Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S3Q5',   'Q1\Listening\Q1S3Q5Controller@saveChoiceRequestPost');
Route::post('/Q1ListeningQ5SubmitData',   'Q1\Listening\Q1S3Q5Controller@getResultToCalculate');

Route::get('/Q1TestResult',   'Q1\Q1ResultController@radarChartResult');
Route::get('/ScoreDetailOption1',  'Q1\ScoreDetailOptionController@show');
Route::post('/saveChoiceRequestTestResultQ1', 'Q1\ScoreDetailOptionController@saveChoiceRequestgrade');
Route::post('/Q1gradecertificatechoiceSubmitData',   'Q1\ScoreDetailOptionController@addGradeOption');
Route::get('/Gradehomepage1', function () { return view('Q1\Gradehomepage'); });
Route::get('/End1Level', function () { return view('Q1\End1Level'); });


Route::get('/Q1VocabularyQ2', 'Q1\Vocabulary\Q1S1Q2Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q2','Q1\Vocabulary\Q1S1Q2Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ2SubmitData', 'Q1\Vocabulary\Q1S1Q2Controller@getResultToCalculate');
Route::get('Q1VocabularyQ2/fetchData', 'Q1\Vocabulary\Q1S1Q2Controller@fetchData');

Route::get('/Q1VocabularyQ3', 'Q1\Vocabulary\Q1S1Q3Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q3','Q1\Vocabulary\Q1S1Q3Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ3SubmitData', 'Q1\Vocabulary\Q1S1Q3Controller@getResultToCalculate');
Route::get('Q1VocabularyQ3/fetchData', 'Q1\Vocabulary\Q1S1Q3Controller@fetchData');


Route::get('/Q1VocabularyQ4', 'Q1\Vocabulary\Q1S1Q4Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q4','Q1\Vocabulary\Q1S1Q4Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ4SubmitData', 'Q1\Vocabulary\Q1S1Q4Controller@getResultToCalculate');
Route::get('Q1VocabularyQ4/fetchData', 'Q1\Vocabulary\Q1S1Q4Controller@fetchData');

Route::get('/Q1VocabularyQ5', 'Q1\Vocabulary\Q1S1Q5Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q5','Q1\Vocabulary\Q1S1Q5Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ5SubmitData', 'Q1\Vocabulary\Q1S1Q5Controller@getResultToCalculate');
Route::get('Q1VocabularyQ5/fetchData', 'Q1\Vocabulary\Q1S1Q5Controller@fetchData');

Route::get('/Q1VocabularyQ6', 'Q1\Vocabulary\Q1S1Q6Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q6','Q1\Vocabulary\Q1S1Q6Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ6SubmitData', 'Q1\Vocabulary\Q1S1Q6Controller@getResultToCalculate');

Route::get('/Q1VocabularyQ7', 'Q1\Vocabulary\Q1S1Q7Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q7','Q1\Vocabulary\Q1S1Q7Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ7SubmitData', 'Q1\Vocabulary\Q1S1Q7Controller@getResultToCalculate');

Route::get('/Q1VocabularyQ8', 'Q1\Vocabulary\Q1S1Q8Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q8','Q1\Vocabulary\Q1S1Q8Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ8SubmitData', 'Q1\Vocabulary\Q1S1Q8Controller@getResultToCalculate');
Route::get('Q1VocabularyQ8/fetchData', 'Q1\Vocabulary\Q1S1Q8Controller@fetchData');

Route::get('/Q1VocabularyQ9', 'Q1\Vocabulary\Q1S1Q9Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q9','Q1\Vocabulary\Q1S1Q9Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ9SubmitData', 'Q1\Vocabulary\Q1S1Q9Controller@getResultToCalculate');
Route::get('Q1VocabularyQ9/fetchData', 'Q1\Vocabulary\Q1S1Q9Controller@fetchData');

Route::get('/Q1VocabularyQ10', 'Q1\Vocabulary\Q1S1Q10Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q10','Q1\Vocabulary\Q1S1Q10Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ10SubmitData', 'Q1\Vocabulary\Q1S1Q10Controller@getResultToCalculate');

Route::get('/Q1VocabularyQ11', 'Q1\Vocabulary\Q1S1Q11Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q11','Q1\Vocabulary\Q1S1Q11Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ11SubmitData', 'Q1\Vocabulary\Q1S1Q11Controller@getResultToCalculate');

Route::get('/Q1VocabularyQ12', 'Q1\Vocabulary\Q1S1Q12Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q12','Q1\Vocabulary\Q1S1Q12Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ12SubmitData', 'Q1\Vocabulary\Q1S1Q12Controller@getResultToCalculate');

Route::get('/Q1VocabularyQ13', 'Q1\Vocabulary\Q1S1Q13Controller@showQuestion');
Route::post('/saveChoiceRequestQ1S1Q13','Q1\Vocabulary\Q1S1Q13Controller@saveChoiceRequestPost');
Route::post('/Q1VocabularyQ13SubmitData', 'Q1\Vocabulary\Q1S1Q13Controller@getResultToCalculate');

Route::post('/timeOutQ1S1', 'Q1\Vocabulary\Q1S1Controller@timeOutCalculation');
?>
 