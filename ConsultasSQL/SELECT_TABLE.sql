/*Prueba de Select para llenar la tabla de compensatorios*/
SELECT
    I.ID_COMPENSATORIO,
    I.ID_FUNCIONARIO,
    TO_CHAR (I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
    TO_CHAR (I.COM_FECHA_FIN) AS COM_FECHA_FIN,
    T.TIP_COM_NOMBRE,
    I.COM_DESCRIPCION_ACTIVIDAD,
    I.COM_USUARIO_FINAL,
    I.COM_ESTADO,
    F.FUN_NOMBRES AS FUN_NOMBRES,
    F.FUN_APELLIDOS AS FUN_APELLIDOS,
    F.FUN_CORREO AS FUN_CORREO,
    F.ID_ROL AS ID_ROL
FROM
    BIG_COMPENSATORIOS I
    INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = 26
    INNER JOIN BIG_TIPO_COMPENSATORIO T ON T.ID_TIPO_COMPENSATORIO = I.ID_TIPO_COMPENSATORIO;

/*-----------------------------------------------------------------------------------------------------*/
/*SACA 10 REGISTROS CUANDO HAY 5, DUPLICA EN ESTE CASO PORQUE SÓLO HAY DOS USUARIOS*/
SELECT
    I.ID_COMPENSATORIO,
    TO_CHAR (I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
    TO_CHAR (I.COM_FECHA_FIN) AS COM_FECHA_FIN,
    TC.TIP_COM_NOMBRE AS TIP_COM_NOMBRE,
    I.COM_DESCRIPCION_ACTIVIDAD,
    I.COM_USUARIO_FINAL,
    I.COM_ESTADO,
    F.FUN_NOMBRES AS FUN_NOMBRES,
    F.FUN_APELLIDOS AS FUN_APELLIDOS,
    F.FUN_CORREO AS FUN_CORREO,
    F.ID_ROL AS ID_ROL
FROM
    BIG_COMPENSATORIOS I
    INNER JOIN BIG_TIPO_COMPENSATORIO TC ON I.ID_TIPO_COMPENSATORIO = TC.ID_TIPO_COMPENSATORIO
    INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = 26;
/*-----------------------------------------------------------------------------------------------------*/
/*Prueba de Select para formatear fecha de compensatorios*/
-- 28/06/24 12:00:00,000000000 PM

SELECT
  LPAD(EXTRACT(YEAR FROM com_fecha_fin), 4, '0') || '/' ||
  LPAD(EXTRACT(MONTH FROM com_fecha_fin), 2, '0') || '/' ||
  LPAD(EXTRACT(DAY FROM com_fecha_fin), 2, '0') || ' ' ||
  LPAD(EXTRACT(HOUR FROM com_fecha_fin), 2, '0') || ':' ||
  LPAD(EXTRACT(MINUTE FROM com_fecha_fin), 2, '0') || ':' ||
  LPAD(EXTRACT(SECOND FROM com_fecha_fin), 2, '0') || ',000000000 ' ||
  CASE WHEN EXTRACT(HOUR FROM com_fecha_fin) >= 12 THEN 'PM' ELSE 'AM' END AS formatted_date_time
FROM big_compensatorios;

-- 20/06/24 1/:0/:0 ,0000 0 

SELECT
  SUBSTR(original_date_time, 1, 10) || '/' ||
  SUBSTR(original_date_time, 12, 2) || '/' ||
  SUBSTR(original_date_time, 15, 2) || ' ' ||
  SUBSTR(original_date_time, 18, 5) || ' ' ||
  SUBSTR(original_date_time, 24, 2) AS formatted_date_time
FROM your_table;

-- 28/06/24 1/:0/:0 ,0000 AM

SELECT
  SUBSTR(original_date_time, 1, 10) || '/' ||
  SUBSTR(original_date_time, 12, 2) || '/' ||
  SUBSTR(original_date_time, 15, 2) || ' ' ||
  CASE
    WHEN SUBSTR(original_date_time, 24, 2) = 'PM' THEN
      CASE
        WHEN SUBSTR(original_date_time, 18, 2) = '12' THEN '12:00'
        ELSE SUBSTR(original_date_time, 18, 5) || ' PM'
      END
    ELSE
      CASE
        WHEN SUBSTR(original_date_time, 18, 2) = '12' THEN '12:00 AM'
        ELSE SUBSTR(original_date_time, 18, 5) || ' AM'
      END
  END AS formatted_date_time
FROM your_table;

-- 

SELECT
  SUBSTR(original_date_time, 1, 10) || '/' ||
  SUBSTR(original_date_time, 12, 2) || '/' ||
  SUBSTR(original_date_time, 15, 2) || ' ' ||
  '12:00 PM' AS formatted_date_time
FROM your_table;

/*-----------------------------------------------------------------------------------------------------*/
/**/
SUM(EXTRACT(HOUR FROM (c.com_fecha_fin - c.com_fecha_inicio)) +
        EXTRACT(MINUTE FROM (c.com_fecha_fin - c.com_fecha_inicio)) / 60
    ) AS HORAS_TOTALES,

/**/
SELECT
  ROUND(SUM(
    EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
    EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
  ), 2) AS HORAS_TOTALES
FROM big_compensatorios I;
/*Modal completo*/
SELECT
  F.FUN_NOMBRES AS FUN_NOMBRES,
  F.FUN_APELLIDOS AS FUN_APELLIDOS,
  F.FUN_CORREO AS FUN_CORREO,
  T.TOM_ESTADO,
  TO_CHAR(T.TOM_FECHA_SOLI, 'DD/MM/YYYY') AS TOM_FECHA_SOLI,
  T.TOM_MOTIVO,
  T.TOM_HORAS_SOLI,
  ROUND(SUM(
    EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
    EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
  ), 2) AS HORAS_TOTALES
FROM BIG_COMPENSATORIOS I
INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
INNER JOIN BIG_TOMA T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO 
WHERE
  T.ID_TOMA = $this->intIdToma
GROUP BY I.ID_FUNCIONARIO, F.FUN_NOMBRES, F.FUN_APELLIDOS, 
F.FUN_CORREO, T.TOM_MOTIVO, T.TOM_FECHA_SOLI, T.TOM_HORAS_SOLI, T.TOM_ESTADO;

/*select para vista de horas funcionando*/
SELECT
  f.fun_nombres AS fun_nombres,
  f.fun_apellidos AS fun_apellidos,
  f.fun_correo AS fun_correo,
  SUM(
    EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
    EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
  ) AS HORAS_TOTALES
FROM big_compensatorios I
INNER JOIN big_funcionarios f ON i.id_funcionario = f.id_funcionario
WHERE
  i.id_funcionario = 26
GROUP BY i.id_funcionario, f.fun_nombres, f.fun_apellidos, f.fun_correo;

/***/
            SELECT
                T.ID_FUNCIONARIO,
                ROUND(SUM(
					EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
					EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
				), 2) AS tHoras
			FROM BIG_COMPENSATORIOS I
            INNER JOIN BIG_TOMA T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE T.ID_FUNCIONARIO = 26 AND T.TOM_ESTADO!=3
			GROUP BY T.ID_FUNCIONARIO;
