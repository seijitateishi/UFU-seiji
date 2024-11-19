// constantes booleanas
#define TRUE 1
#define FALSE 0

// constantes para nome de token
#define TOK_ID 0
#define TOK_RELOP 1
#define TOK_SEP 2
#define TOK_NUM_INT 3
#define TOK_NUM_FLOAT 4
#define TOK_COMMENT 5
#define TOK_EOF 6
#define TOK_ASSIGN 7
#define TOK_BEGIN 8
#define TOK_END 9
#define TOK_WHILE 10
#define TOK_REPEAT 11
#define TOK_UNTIL 12
#define TOK_ERROR 13

#define SOMA 0
#define SUB 1
#define MULT 2
#define DIV 3

#define PARESQ 0
#define PARDIR 1

// estrutura de um token
typedef struct {
    int tipo;
    int valor;
}Token;

// função para criar um token
extern Token* token();

// função do analisador léxico
extern Token* yylex();
