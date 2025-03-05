/* ===== Arquivo tokens.h ===== */
// Constantes booleanas
#define TRUE 1
#define FALSE 0

// Constantes para nome de token
#define TOK_ID 0
#define TOK_RELOP 1
#define TOK_NUM_INT 2
#define TOK_NUM_FLOAT 3
#define TOK_ATRIB 4
#define TOK_BEGIN 5
#define TOK_END 6
#define TOK_WHILE 7
#define TOK_REPEAT 8
#define TOK_UNTIL 9
#define TOK_COMMENT 10
#define TOK_SEPARATOR 11
#define TOK_EOF 12
#define TOK_ERRO 13

// Estrutura de um token
typedef struct
{
    int tipo;
    char *valor;
} Token;

// Funcao para criar um token
extern Token *token(int tipo, char *valor);

// Funcao do analisador lexico
extern Token *yylex();