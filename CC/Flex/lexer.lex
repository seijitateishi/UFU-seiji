%option noyywrap
%option nodefault
%option outfile="lexer.c" header-file="lexer.h"

%{
#include "lexer.h"
#include <string.h>
%}

/* Regular expressions */
DIGIT       [0-9]
LETTER      [a-zA-Z]
ID          {LETTER}({LETTER}|{DIGIT})*
NUM_INT     {DIGIT}+
NUM_FLOAT   {DIGIT}+\.{DIGIT}+
WHITESPACE  [ \t\n]
RELOP       "<"|">"|"<="|">="|"="|"<>"

%%

"begin"     { return create_token(TOK_BEGIN, NULL); }
"end"       { return create_token(TOK_END, NULL); }
"while"     { return create_token(TOK_WHILE, NULL); }
"repeat"    { return create_token(TOK_REPEAT, NULL); }
"until"     { return create_token(TOK_UNTIL, NULL); }
":="        { return create_token(TOK_ASSIGN, NULL); }

{ID}        { return create_token(TOK_ID, yytext); }
{NUM_INT}   { return create_token(TOK_NUM_INT, yytext); }
{NUM_FLOAT} { return create_token(TOK_NUM_FLOAT, yytext); }
{RELOP}     { return create_token(TOK_RELOP, yytext); }

"/*"        { /* Comment handling */
                int c;
                while((c = input()) != 0) {
                    if(c == '*') {
                        if((c = input()) == '/')
                            break;
                        unput(c);
                    }
                }
            }

{WHITESPACE}    { /* Ignore whitespace */ }

<<EOF>>     { return create_token(TOK_EOF, NULL); }

.           { return create_token(TOK_ERROR, NULL); }

%%
