#include <unistd.h>
#include <stdio.h>
#include <string.h>

int main(int argc, char **argv) {
  int i;
  char * argList[argc+2];

  argList[0] = "manage.pl";
  argList[1] = "/home/cschatz/sn/manage.pl";

  for (i = 1; i < argc; i++)
    {      
      argList[i+1] = argv[i];
    }
  argList[argc+1] = (char *) 0;

  execv("/usr/bin/perl", argList);
  return 0;
}
