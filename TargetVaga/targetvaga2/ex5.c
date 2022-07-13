#include <string.h>
#include <stdio.h>
#include <stdlib.h>
int main(){
    char array[20] = "abcde";
    char final[20];
    int j = 0;
    while (array[j] != '\0')
    {
        j++;
    }
    for (int i = 0; i < j+1; i++)
    {
        final[i] = array[j-i];
    }
    for (int i = 0; i < j+1; i++)
    {
        printf("%c\n", final[i]);
    }
    return 0;
}