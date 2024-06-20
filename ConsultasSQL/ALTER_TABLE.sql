/*Editar campo de tabla a numerico y hacerlo llave foránea de otra tabla*/
ALTER TABLE BIG_COMPENSATORIOS
MODIFY COM_ACTIVIDAD_DESARROLLAR NUMBER
ADD CONSTRAINT FK_BIG_COMPENSATORIOS_BIG_TIPO_COMPENSATORIO
FOREIGN KEY (COM_ACTIVIDAD_DESARROLLAR)
REFERENCES BIG_TIPO_COMPENSATORIO (ID_TIPO_COMPENSATORIO);
/*Creamos PK antes y Edité el campo directamente en la DB, para solo crear la llave foránea*/
ALTER TABLE BIG_COMPENSATORIOS
ADD CONSTRAINT BIG_COM_BIG_TIP_COM_FK
FOREIGN KEY (ID_TIPO_COMPENSATORIO)
REFERENCES BIG_TIPO_COMPENSATORIO (ID_TIPO_COMPENSATORIO);
/*------------------------------------------------------------*/
