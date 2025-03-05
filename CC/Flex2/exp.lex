%option noyywrap
%option nodefault
%option outfile="lexer.c" header-file="lexer.h"
%{
    #include <stdlib.h>
    #include <string.h>
    #include "exp.h"
    #include "exp.c"
%}

ID [a-zA-Z][a-zA-Z0-9]*
NUM_INT [0-9]+
NUM_FLOAT [0-9]+\.[0-9]+
RELOP "<"|">"|"<="|">="|"=="|"!="
COMMENT "/*"([^*]|"*"[^/])*"*/"

%%
[[:space:]] { return token(TOK_SEPARATOR, NULL); } /* espaco, \t, \n */

{ID} {
    if (strcmp(yytext, "begin") == 0) return token(TOK_BEGIN, NULL);
    else if (strcmp(yytext, "end") == 0) return token(TOK_END, NULL);
    else if (strcmp(yytext, "while") == 0) return token(TOK_WHILE, NULL);
    else if (strcmp(yytext, "repeat") == 0) return token(TOK_REPEAT, NULL);
    else if (strcmp(yytext, "until") == 0) return token(TOK_UNTIL, NULL);
    else return token(TOK_ID, strdup(yytext));
}

{NUM_INT} { return token(TOK_NUM_INT, strdup(yytext)); }
{NUM_FLOAT} { return token(TOK_NUM_FLOAT, strdup(yytext)); }
{RELOP} { return token(TOK_RELOP, strdup(yytext)); }
":=" { return token(TOK_ATRIB, NULL); }

{COMMENT} { return token(TOK_COMMENT, NULL); }

<<EOF>> { return token(TOK_EOF, NULL); }

. { return token(TOK_ERRO, strdup(yytext)); }
%%

// Variavel global para um token
Token tok;

Token *token(int tipo, char *valor) {
    tok.tipo = tipo;
    tok.valor = valor;
    return &tok;
}
