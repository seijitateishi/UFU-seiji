#include "lexer.h"
#include "exp.h"
#include <stdio.h>

// Carrega uma string como entrada
YY_BUFFER_STATE buffer;

void inicializa(char *str)
{
    buffer = yy_scan_string(str);
}

Token *proximo_token()
{
    return yylex();
}

void imprime_token(Token *tok)
{
}

int main(int argc, char **argv)
{
    Token *tok;
    char entrada[200];

    printf("\nAnalise Lexica da expressao: ");
    fgets(entrada, 200, stdin);
    inicializa(entrada);

    tok = proximo_token();
    while (tok != NULL)
    {
        imprime_token(tok);
        tok = proximo_token();
    }

    return 0;
}
