<?php  
    // use PHPMailer\PHPMailer\PHPMailer;

	//Retorla la url del proyecto
	function base_url(){
		return BASE_URL;
	}
    //Retorla la url de Assets
    function media(){
        return BASE_URL."/Assets";
    }
    function headerAdmin($data=""){
        $view_header = "Views/Template/header_admin.php";
        require_once ($view_header);
    }
    function footerAdmin($data=""){
        $view_footer = "Views/Template/footer_admin.php";
        require_once ($view_footer);        
    }
    function getModal(string $nameModal, $data){
        $view_modal = "Views/Template/Modals/{$nameModal}.php";
        require_once $view_modal;     
    }
    function getPermisos(string $MOD_CODIGO){
        require_once ("Models/PermisosModel.php");
        $objPermisos = new PermisosModel();
        $ROL_CODIGO = $_SESSION['userData']['ROL_CODIGO'];
        //traer todos los permisos que tiene el rol que esta loguado en el sistema
        $arrPermisos = $objPermisos->permisosModulo($ROL_CODIGO);

        $permisos = '';
        $permisosMod = '';

        //aqui se realiza la busqueda del modulo actual que quiere cargar el usuario
        if(count($arrPermisos) > 0 ){
            $permisos = $arrPermisos;
            $permisosMod = isset($arrPermisos[$MOD_CODIGO]) ? $arrPermisos[$MOD_CODIGO] : "";
        }
        //se almacenan todos los permisos que tiene el rol cargados
        $_SESSION['permisos'] = $permisos;
        //se almacenan los permisos del modulo que esta vizualizando el usuario
        $_SESSION['permisosMod'] = $permisosMod;

        /*
        dep($_SESSION['permisos']);
        echo "<br>";
        dep($_SESSION['permisosMod']);
        */
        
    }
    function sessionUser(int $ID_FUNCIONARIO){
        require_once ("Models/LoginModel.php");
        $objLogin = new LoginModel();
        $request = $objLogin->sessionLogin($ID_FUNCIONARIO);
        return $request;
    }

    function uploadImage(array $data, string $name){
        $url_temp = $data['tmp_name'];
        $destino = 'Assets/images/uploads/'.$name;        
        $move = move_uploaded_file($url_temp, $destino);
        return $move;
    }

    function deleteFile(string $name){
        unlink('Assets/images/uploads/'.$name);
    }

    //Envio de correos
    function sendEmail($data,$template){
        $asunto = $data['asunto'];
        $emailDestino = $data['email'];
        $empresa = NOMBRE_REMITENTE;
        $remitente = EMAIL_REMITENTE;
        //ENVIO DE CORREO
        $de = "MIME-Version: 1.0\r\n";
        $de .= "Content-type: text/html; charset=UTF-8\r\n";
        $de .= "From: {$empresa} <{$remitente}>\r\n";
        ob_start();
        require_once("Views/Template/Email/".$template.".php");
        $mensaje = ob_get_clean();
        $send = mail($emailDestino, $asunto, $mensaje, $de);
        return $send;
    }
    
    function enviarMail($remitente, $destinatario, $asunto, $tipoMensaje, $datos = null) {
        $mail = new PHPMailer(true);
    
        try {
            $mail->SMTPDebug = 0;
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@asmetsalud.com';
            $mail->Password = '4sm3t+2019';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
    
            $mail->setFrom("noreply@asmetsalud.com", "Asmet Salud EPS");
            $mail->addAddress($destinatario);
    
            $mail->isHTML(true);
            $mail->Subject = $asunto;
    
            // Obtener el contenido HTML del mensaje utilizando la función generarHTML()
            $html = generarHTML($tipoMensaje, $datos);
            $mail->Body = $html;
    
            if (!$mail->Send()) {
                // Mensaje no enviado
                return '0';
            }
    
            // Mensaje enviado exitosamente
            return '1';
        } catch (Exception $e) {
            // Captura cualquier excepción y la muestra
            return '0';
        }
    }
    
    function generarHTML($tipoMensaje, $datos = null) {
        $mensajes = [
            'aprobacion' => 'Su compensatorio ha sido aprobado.',
            'rechazo' => 'Su compensatorio ha sido rechazado.',
            'solicitud' => 'Solicito el siguiente compensatorio:',
            'solicitud_horas' => 'Solicito horas de compensatorio:',
            'Aprobacion de horas' => 'Su solicitud de horas fue aprobada',
            'Rechazo de horas' => 'Su solicitud de horas fue rechazada'
        ];
    
        // Verificar si el tipo de mensaje existe en el array de mensajes
        if (array_key_exists($tipoMensaje, $mensajes)) {
            $mensaje = $mensajes[$tipoMensaje];
    
            $html = '
                <html>
                <body>';
    
            // Agregar el elemento solo para solicitud y solicitud_horas
            if ($tipoMensaje == 'solicitud' || $tipoMensaje == 'solicitud_horas') {
                $html .= '<p>El usuario: '.$datos['Funcionario'].'</p>';
            } elseif ($tipoMensaje == 'aprobacion' || $tipoMensaje == 'rechazo' || $tipoMensaje == 'Aprobacion de horas' || $tipoMensaje == 'Rechazo de horas') {
                $html .= '<p>Usuario: ' . $datos['FUN_NOMBRES'] . ' ' . $datos['FUN_APELLIDOS'] . '</p>';
            }
    
            $html .= '<p>' . $mensaje . '</p>';
    
            if ($tipoMensaje == 'solicitud' && $datos) {
                // Agregar el bloque de datos específico para la solicitud
                $html .= '
                <ul>
                    <li>Fecha hora inicio: ' . $datos['FechaInicio'] . '</li>
                    <li>Fecha hora final: ' . $datos['FechaFin'] . '</li>
                    <li>Actividad: ' . $datos['Actividad'] . '</li>
                    <li>¿Quien requiere el trabajo?: ' . $datos['UsuarioTrabajo'] . '</li>
                    <li>Descripcion actividad: ' . $datos['DescripcionAc'] . '</li>
                </ul>';
            } elseif ($tipoMensaje == 'solicitud_horas' && $datos) { // HTML específico para solicitud de horas
                $html .= '
                <ul>
                    <li>Motivo de la solicitud: ' . $datos['MotivoSolicitud'] . '</li>
                    <li>Fecha de la solicitud: ' . $datos['FechaSolicitud'] . '</li>
                    <li>Horas a solicitar: ' . $datos['HorasSolicitar'] . '</li>
                </ul>';
            } elseif ($tipoMensaje == 'aprobacion' && $datos){
                $html .='
                <p>Detalles del compensatorio:</p>
                <ul>
                    <li>Nombres: ' . $datos['FUN_NOMBRES'] . '</li>
                    <li>Apellidos: ' . $datos['FUN_APELLIDOS'] . '</li>
                    <li>Usuario: ' . $datos['FUN_USUARIO'] . '</li>
                    <li>Correo Electrónico: ' . $datos['FUN_CORREO'] . '</li>
                    <li>Fecha de inicio: ' . $datos['COM_FECHA_INICIO'] . '</li>
                    <li>Fecha de fin: ' . $datos['COM_FECHA_FIN'] . '</li>
                    <li>Actividad: ' . $datos['COM_ACTIVIDAD_DESARROLLAR'] . '</li>
                    <li>Descripcion actividad: ' . $datos['COM_DESCRIPCION_ACTIVIDAD'] . '</li>
                </ul>

                <p>Compensatorio aprobado por: ' .$_SESSION['userData']['FUN_NOMBRES'] .' '.$_SESSION['userData']['FUN_APELLIDOS'] .'</p>';
                
            } elseif ($tipoMensaje == 'rechazo' && $datos){
                $html .='
                <p>Detalles del compensatorio:</p>
                <ul>
                    <li>Nombres: ' . $datos['FUN_NOMBRES'] . '</li>
                    <li>Apellidos: ' . $datos['FUN_APELLIDOS'] . '</li>
                    <li>Usuario: ' . $datos['FUN_USUARIO'] . '</li>
                    <li>Correo Electrónico: ' . $datos['FUN_CORREO'] . '</li>
                    <li>Fecha de inicio: ' . $datos['COM_FECHA_INICIO'] . '</li>
                    <li>Fecha de fin: ' . $datos['COM_FECHA_FIN'] . '</li>
                    <li>Actividad: ' . $datos['COM_ACTIVIDAD_DESARROLLAR'] . '</li>
                    <li>Descripcion actividad: ' . $datos['COM_DESCRIPCION_ACTIVIDAD'] . '</li>
                </ul>

                <p>Compensatorio rechazado por: ' .$_SESSION['userData']['FUN_NOMBRES'] .' '.$_SESSION['userData']['FUN_APELLIDOS'] .'</p>';

            } elseif ($tipoMensaje == 'Aprobacion de horas' && $datos){
                $html .='
                <p>Datos de la solicitud:</p>
                <ul>
                    <li>Nombres: ' . $datos['FUN_NOMBRES'] . '</li>
                    <li>Apellidos: ' . $datos['FUN_APELLIDOS'] . '</li>
                    <li>Correo: ' . $datos['FUN_CORREO'] . '</li>
                    <li>Usuario: ' . $datos['FUN_USUARIO'] . '</li>
                    <li>Motivo: ' . $datos['TOM_MOTIVO'] . '</li>
                    <li>Fecha de solicitud: ' . $datos['TOM_FECHA_SOLI'] . '</li>
                    <li>Horas solicitadas: ' . $datos['TOM_HORAS_SOLI'] . '</li>
                </ul>

                <p>Horas aprobadas por: ' .$_SESSION['userData']['FUN_NOMBRES'] .' '.$_SESSION['userData']['FUN_APELLIDOS'] .'</p>';

            } elseif ($tipoMensaje == 'Rechazo de horas' && $datos){
                $html .='
                <p>Detalles de la solicitud:</p>
                <ul>
                    <li>Nombres: ' . $datos['FUN_NOMBRES'] . '</li>
                    <li>Apellidos: ' . $datos['FUN_APELLIDOS'] . '</li>
                    <li>Correo: ' . $datos['FUN_CORREO'] . '</li>
                    <li>Usuario: ' . $datos['FUN_USUARIO'] . '</li>
                    <li>Motivo: ' . $datos['TOM_MOTIVO'] . '</li>
                    <li>Fecha de solicitud: ' . $datos['TOM_FECHA_SOLI'] . '</li>
                    <li>Horas solicitadas: ' . $datos['TOM_HORAS_SOLI'] . '</li>
                </ul>

                <p>Horas rechazadas por: ' .$_SESSION['userData']['FUN_NOMBRES'] .' '.$_SESSION['userData']['FUN_APELLIDOS'] .'</p>';
            }
    
            $html .= '<p>Gracias por su colaboración.</p>
                </body>
                </html>';
    
            return $html;
        } else {
            // Manejar tipos de mensaje no válidos
            return 'Tipo de mensaje no válido';
        }
    }
    
    //Funcion para formatear datos tipo date
    function formatearFechaUsuComparar($dato,$formatoResultado){
        $date=date_create_from_format('d-m-y',$dato);
        if($date==""){
            $date=date_create_from_format('d/m/y',$dato);
        }
        if($date==""){
            $date=date_create_from_format('d-M-y',$dato);
        }
        if($date==""){
            $date=date_create_from_format('d/M/y',$dato);
        }
        if($date==""){
            $date=date_create_from_format('d/m/Y',$dato);
        }
        if($date==""){
            $date=date_create_from_format('d-m-Y',$dato);
        }
        $date=date_format($date,$formatoResultado);
        
        return $date;
        
    }

    //Funcion para formatear datos tipo datetime-local
    function formatearFechaYHora($dato, $formatoResultado) {
        $date = date_create_from_format('d-M-y h.i.s.u A', $dato);
        if ($date === false) {
            $date = date_create_from_format('d-M-y h.i.s.u A', $dato);
        }
        if ($date === false) {
            $date = date_create_from_format('Y-m-d H:i A', $dato);
        }
        if ($date === false) {
            $date = date_create_from_format('d/m/Y - h:i A', $dato);
        }
        if ($date === false) {
            $date = date_create_from_format('d/m/Y H:i A', $dato);
        }
        if ($date === false) {
            $date = date_create_from_format('d-m-Y h:i:s A', $dato);
        }
        // Formato especial para establecer y mostrar fechas y horas
        if ($date === false) { 
            $date = date_create_from_format('Y-m-d\TH:i', $dato);
        }
        
        if ($date !== false) {
            return date_format($date, $formatoResultado);
        } else {
            return $dato; // Devuelve la fecha original sin formato
        }
    }
     
    //Muestra información formateada
	function dep($data){
        $format  = print_r('<pre>');
        $format .= print_r($data);
        $format .= print_r('</pre>');
        return $format;
    }

    //Elimina exceso de espacios entre palabras y palabras reservadas
    function strClean($strCadena){
        $string = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $strCadena);
        $string = trim($string); //Elimina espacios en blanco al inicio y al final
        $string = stripslashes($string); // Elimina las \ invertidas
        $string = str_ireplace("<script>","",$string);
        $string = str_ireplace("</script>","",$string);
        $string = str_ireplace("<script src>","",$string);
        $string = str_ireplace("<script type=>","",$string);
        $string = str_ireplace("SELECT * FROM","",$string);
        $string = str_ireplace("DELETE FROM","",$string);
        $string = str_ireplace("INSERT INTO","",$string);
        $string = str_ireplace("SELECT COUNT(*) FROM","",$string);
        $string = str_ireplace("DROP TABLE","",$string);
        $string = str_ireplace("OR '1'='1","",$string);
        $string = str_ireplace('OR "1"="1"',"",$string);
        $string = str_ireplace('OR ´1´=´1´',"",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("LIKE '","",$string);
        $string = str_ireplace('LIKE "',"",$string);
        $string = str_ireplace("LIKE ´","",$string);
        $string = str_ireplace("OR 'a'='a","",$string);
        $string = str_ireplace('OR "a"="a',"",$string);
        $string = str_ireplace("OR ´a´=´a","",$string);
        $string = str_ireplace("OR ´a´=´a","",$string);
        $string = str_ireplace("--","",$string);
        $string = str_ireplace("^","",$string);
        $string = str_ireplace("[","",$string);
        $string = str_ireplace("]","",$string);
        $string = str_ireplace("==","",$string);
        return $string;
    }

    //Genera una contraseña de 10 caracteres
	function passGenerator($length = 10){
        $pass = "";
        $longitudPass=$length;
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena=strlen($cadena);

        for($i=1; $i<=$longitudPass; $i++)
        {
            $pos = rand(0,$longitudCadena-1);
            $pass .= substr($cadena,$pos,1);
        }
        return $pass;
    }

    //Genera un token
    function token(){
        $r1 = bin2hex(random_bytes(10));
        $r2 = bin2hex(random_bytes(10));
        $r3 = bin2hex(random_bytes(10));
        $r4 = bin2hex(random_bytes(10));
        $token = $r1.'-'.$r2.'-'.$r3.'-'.$r4;
        return $token;
    }

    //Formato para valores monetarios
    function formatMoney($cantidad){
        $cantidad = number_format($cantidad,2,SPD,SPM);
        return $cantidad;
    }
 ?>