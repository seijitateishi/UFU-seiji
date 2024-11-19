%option noyywrap
%option nodefault
%option outfile="lexer.c" header-file="lexer.h"

%{
#include "exp.h"
#include <string.h>
%}

DIGITO [0-9]
LETRA [a-zA-Z]
ID [_a-z]({LETRA}|{DIGITO})*
NUM_INT [0-9]+
NUM_FLOAT [0-9]+\.[0-9]+
COMENTARIO  "/*"([^*]|\*+[^/*])*\*+"/"
ATRIBUICAO       ":="


%%

[[:space:]\n\t]+ { return token(TOK_SEP, NULL); }
{ID} { return token(TOK_ID, yytext); }
{NUM_INT} { return token(TOK_NUM_INT, yytext); }
{NUM_FLOAT} { return token(TOK_NUM_FLOAT, yytext); }
(==|!=|<=|>=|<|>) { return token(TOK_RELOP, yytext); }
{COMENTARIO} { return token(TOK_COMMENT, NULL); }
{ATRIBUICAO} { return token(TOK_ASSIGN, NULL); }
. { return token(TOK_ERROR, 0); }
<<EOF>> {return token(TOK_EOF, NULL); }
begin { return token(TOK_BEGIN, NULL); }
end { return token(TOK_END, NULL); }
while { return token(TOK_WHILE, NULL); }
repeat { return token(TOK_REPEAT, NULL); }
until { return token(TOK_REPEAT, NULL); }

%%

Token tok;

Token* token(int tipo, int valor) {
    tok.tipo = tipo;
    tok.valor = valor;
    return &tok;
}
