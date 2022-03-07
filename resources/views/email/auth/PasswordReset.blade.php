@section('content')
<p>
    パスワードリセットの申請が行われました。<br>
    以下のURLにアクセスし，パスコードのリセットを行ってください。
</p>
<p>
    <a href="https://auth.licsss.com/reset/?id={{$ResetId}}">パスワードリセットURL(https://auth.licsss.com/reset/?id={{$ResetId}})</a>
</p>
<p>
    パスワードリセットの申請を行った記憶がない方は，<a href="https://auth.licsss.com/reset/?id={{$ResetId}}&delete=true">https://auth.licsss.com/reset/?id={{$ResetId}}&delete=true</a>にアクセスし，パスワードリセットを無効にしてください。<br>
    また，他の方にログイン用メールアドレスやパスワードを推測されている可能性が高いため，メールアドレスやパスワードの変更をおすすめします。
</p>
@endsection('content');