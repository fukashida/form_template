<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    
<div style="display:none">
<?php
// var_dump($_POST);

// 変数の初期化
$page_flag = 0;
$clean = array();

// サニタイズ
if( !empty($_POST) ) {
	foreach( $_POST as $key => $value ) {
		$clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
	}
}

// 変数とタイムゾーンを初期化
$header = null;
$auto_reply_subject = null;
$auto_reply_text = null;
$admin_reply_subject = null;
$admin_reply_text = null;
date_default_timezone_set('Asia/Tokyo');

// 宛先 ※同時に指定
$to = "ここにメールアドレスを入れる";

// ヘッダー情報を設定
$header = "MIME-Version: 1.0\n";
$header .= "From: 〇〇 <送り先メアド>\n";
$header .= "Reply-To: 〇〇 <送り先メアド>\n";

// 運営側へ送るメールの件名
$admin_reply_subject = "〇〇無料カウンセリングへのお問い合わせがありました。";

// 本文を設定
$admin_reply_text = "\n";
$admin_reply_text .= "以下の内容でWEBサイトへのお問い合わせがありました。\n";
$admin_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n\n";
$admin_reply_text .= "お申込み番号：" . $_POST['application_number'] . "\n\n";
$admin_reply_text .= "名前：" . $_POST['your_name'] . "\n";
$admin_reply_text .= "メールアドレス：" . $_POST['email'] . "\n";
$admin_reply_text .= "第一希望日程 時間：" . $_POST['datetime_local01'] . "\n";
$admin_reply_text .= "第二希望日程 時間：" . $_POST['datetime_local02'] . "\n";
$admin_reply_text .= "ご検討段階について：" . $_POST['stage'] . "\n";
$admin_reply_text .= "\n";
$admin_reply_text .= "ご相談内容：" . $_POST['message'] . "\n\n";
$admin_reply_text .= "--\n\n";
$admin_reply_text .= "問い合わせ内容一覧\n";
$admin_reply_text .= "<< 紐付けたスプレッドシートのリンク >> \n\n";
$admin_reply_text .= "このメールは 〇〇 (HPアドレス) の無料カウンセリングフォームから送信されました\n";

// 運営側へメール送信
mb_send_mail( $to , $admin_reply_subject, $admin_reply_text, $header);

session_start();
if( !empty($_SESSION['page']) && $_SESSION['page'] === true ) {


if(count($_POST)){
    $url = 'ここにスプレッドシートのウェブデプロイリンクを挿入';
    $data = array(
        'your_name' => $_POST['your_name'],
        'email' => $_POST['email'],
        'datetime_local01' => $_POST['datetime_local01'],
        'datetime_local02' => $_POST['datetime_local02'],
        'stage' => $_POST['stage'],
        'message' => $_POST['message'],
    );
    $context = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
            'content' => http_build_query($data)
        )
    );
    $response_json = file_get_contents($url, false, stream_context_create($context));
    $response_data = json_decode($response_json);
    var_dump($response_data);
}


} else {
    $page_flag = 0;
}
// セッションの削除
unset($_SESSION['page']);
?>
</div>


<section class="complete">
    <div class="container">
        <div class="sub-container">
            <h2 class="std7">送信完了</h2>
            <p>お問い合わせありがとうございます。<br>
            ご入力いただいた内容を確認後、担当者よりご連絡させていただきます。
            </p>
            <a class="button" href="">トップページに戻る</a>
        </div>
    </div>
</section>

</body>  
</html>