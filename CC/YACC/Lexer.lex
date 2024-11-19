%option noyywrap
%option outfile="Lexer.c" header-file="Lexer.h"
%{
    #define YYSTYPE double       // Define tipo yylval (extern YYSTYPE yylval no y.tab.h)
    #include "y.tab.h"           // Biblioteca Analisador Sintático
%}

DIGITO [0-9]

%%
[ \t]           { /* ignora tabulação e espaço */ }

{DIGITO}+([.]{DIGITO}+)?  { yylval = atof(yytext); return NUM; }

"+"             { return yytext[0]; }
"-"             { return yytext[0]; }
"*"             { return yytext[0]; }
"/"             { return yytext[0]; }

\n              { return yytext[0]; }

%%

// Seção de Código VAZIA
