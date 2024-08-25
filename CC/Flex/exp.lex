%option noyywrap
%option nodefault
%option outfile="lexer.c" header-file="lexer.h"

%{
#include "exp.h"
#include <string.h>
%}

ID [a-zA-Z_][a-zA-Z0-9_]*
NUM_INT [0-9]+
NUM_FLOAT [0-9]+\.[0-9]+

%%

[[:space:]\n\t]+ { return token(TOK_SEP, NULL) }
{ID} { return token(TOK_ID, yytext); }
{NUM_INT} { return token(TOK_NUM_INT, yytext); }
{NUM_FLOAT} { return token(TOK_NUM_FLOAT, yytext); }
(==|!=|<=|>=|<|>) { return token(TOK_RELOP, yytext); }
\/\*([^*]|\*+[^*/])*\*+\/ { return token(TOK_ASSIGN, NULL)}
:= { return token(TOK_ASSIGN, NULL); }
begin { return token(TOK_BEGIN, NULL); }
end { return token(TOK_END, NULL); }
while { return token(TOK_WHILE, NULL); }
repeat { return token(TOK_REPEAT, NULL); }
until { return token(TOK_UNTIL, NULL, NULL); }
. { return token(TOK_ERROR, 0); }
<<EOF>> {return token(TOK_EOF, NULL)}

%%

// variável global para um token
Token tok;

Token* token(int tipo, char* lexema) {
    tok.tipo = tipo;
    tok.lexema = lexema;
    return &tok;
}
