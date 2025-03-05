#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "lexer.h"
#include "exp.h"

/* Carrega um arquivo como entrada */
void inicializa_arquivo(FILE *arquivo)
{
    yyin = arquivo;
}

Token *proximo_token()
{
    return yylex();
}

void imprime_token(Token *tok)
{
    switch (tok->tipo)
    {
    case TOK_ID:
        printf("<ID, %s>\n", tok->valor);
        break;
    case TOK_RELOP:
        printf("<RELOP, %s>\n", tok->valor);
        break;
    case TOK_NUM_INT:
        printf("<NUM_INT, %s>\n", tok->valor);
        break;
    case TOK_NUM_FLOAT:
        printf("<NUM_FLOAT, %s>\n", tok->valor);
        break;
    case TOK_ATRIB:
        printf("<ATRIBUICAO, ->\n");
        break;
    case TOK_BEGIN:
        printf("<BEGIN, ->\n");
        break;
    case TOK_END:
        printf("<END, ->\n");
        break;
    case TOK_WHILE:
        printf("<WHILE, ->\n");
        break;
    case TOK_REPEAT:
        printf("<REPEAT, ->\n");
        break;
    case TOK_UNTIL:
        printf("<UNTIL, ->\n");
        break;
    case TOK_EOF:
        printf("<EOF, ->\n");
        break;
    case TOK_ERRO:
        printf("<ERRO, ->\n");
        break;
    default:
        printf("<TOKEN DESCONHECIDO, ->\n");
    }
}

int main(int argc, char **argv)
{
    Token *tok;
    FILE *arquivo;
    char nome_arquivo[200];

    // Solicita o nome do arquivo ao usuário
    printf("Digite o nome do arquivo a ser analisado: ");
    scanf("%s", nome_arquivo);

    // Abre o arquivo para leitura
    arquivo = fopen(nome_arquivo, "r");
    if (arquivo == NULL)
    {
        printf("Erro ao abrir o arquivo %s\n", nome_arquivo);
        return 1;
    }

    // Inicializa o analisador léxico com o arquivo
    inicializa_arquivo(arquivo);

    printf("\nAnalise lexica do arquivo %s:\n", nome_arquivo);

    // Processo de reconhecimento de tokens
    do
    {
        tok = proximo_token();
        imprime_token(tok);

        // Libera a memória alocada para valor, se existir
        if (tok->valor != NULL)
        {
            free(tok->valor);
            tok->valor = NULL;
        }
    } while (tok->tipo != TOK_EOF);

    // Fecha o arquivo
    fclose(arquivo);

    return 0;
}