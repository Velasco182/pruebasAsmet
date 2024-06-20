/*Prueba de Select para llenar la tabla de compensatorios*/
SELECT
	I.ID_COMPENSATORIO,
	I.ID_FUNCIONARIO,
    TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
    TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
    T.TIP_COM_NOMBRE,
    I.COM_DESCRIPCION_ACTIVIDAD,
    I.COM_USUARIO_FINAL,
    I.COM_ESTADO,
    F.FUN_NOMBRES AS FUN_NOMBRES,
    F.FUN_APELLIDOS AS FUN_APELLIDOS,
	F.FUN_CORREO AS FUN_CORREO,
	F.ID_ROL AS ID_ROL
FROM BIG_COMPENSATORIOS I
INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
INNER JOIN BIG_TIPO_COMPENSATORIO T ON T.ID_TIPO_COMPENSATORIO = I.ID_TIPO_COMPENSATORIO;
/*-----------------------------------------------------------------------------------------------------*/