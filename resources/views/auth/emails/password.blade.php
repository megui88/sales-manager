<p>Click en el siguiente link para cambiar la contraseña: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a></p>
<p>Si no puede hacer click copie y pegue en su barra de navegacion el siguiente link: {{ $link }} </p>
