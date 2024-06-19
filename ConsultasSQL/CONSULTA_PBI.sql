select * from big_funcionarios;

/*INSERT INTO BIG_FUNCIONARIOS
				(
					FUN_IDENTIFICACION,
					FUN_NOMBRES,
					FUN_APELLIDOS,
					FUN_USUARIO,
					FUN_CORREO,
					FUN_PASSWORD,
					ID_ROL,
					FUN_ESTADO
				) 
				VALUES
				(
					0, "Ruben", "Velasco", "ruben.velasco", "aprendiz.bi@asmetsalud.com", "prueba123", 2, "1"
				);*/
                
/*INSERT INTO BIG_FUNCIONARIOS
				(
                    ID_COMPENSATORIO,
					FUN_NOMBRES,
					FUN_APELLIDOS,
					FUN_USUARIO,
					FUN_CORREO,
					FUN_PASSWORD,
					ID_ROL,
					FUN_ESTADO
				) 
				VALUES
				(
					0, "Ruben", "Velasco", "ruben.velasco", "aprendiz.bi@asmetsalud.com", "prueba123", 2, "1"
				);*/
/*CREAR TABLA DE TIPO DE COMPENSATORIOS*/
CREATE TABLE "CONSULTA_PBI"."BIG_TIPO_COMPENSATORIO" 
   (	
	"ID_TIPO_COMPENSATORIO" NUMBER(*,0) NOT NULL ENABLE, 
	"TIP_COM_NOMBRE" VARCHAR2(200 BYTE), 
	"TIP_COM_DESCRIPCION" VARCHAR2(200 BYTE), 
	"ID_COMPENSATORIO " NUMBER(*,0) NOT NULL ENABLE, 
	"FUN_ADMIN" VARCHAR2(20 BYTE) DEFAULT 0, 
	CONSTRAINT "BIG_FUNCIONARIOS_PK" PRIMARY KEY ("ID_FUNCIONARIO")
  	USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  	STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  	PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  	BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  	TABLESPACE "SIAS_DATA"  ENABLE, 
	 CONSTRAINT "BIG_FUNCIONARIOS_BIG_ROLES_FK" FOREIGN KEY ("ID_ROL")
	  REFERENCES "CONSULTA_PBI"."BIG_ROLES" ("ID_ROL") ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "SIAS_DATA" ;

  CREATE OR REPLACE TRIGGER "CONSULTA_PBI"."BIG_FUNCIONARIOS_ID_FUNCIONARI" BEFORE
    INSERT ON big_funcionarios
    FOR EACH ROW
     WHEN ( new.id_funcionario IS NULL ) BEGIN
    :new.id_funcionario := big_funcionarios_id_funcionari.nextval;
END;

/
ALTER TRIGGER "CONSULTA_PBI"."BIG_FUNCIONARIOS_ID_FUNCIONARI" ENABLE;