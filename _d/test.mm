page.action: test.php
author: sdfasf
page.title: Test-Seite

Test-Seite
====================================

[lnk ll1parser Parser-Generator]

Mathematik
---------------------------------------

graph/ width="200" height="200" src="d.svg" 
script='initPicture(-1,5, -1 , 5)
line([1,1], [3,0] )
line([3,3], [3,0] )
line([3,3], [1,1] )
axes()' 
/graph


graph/ width="117" height="117" src="d.svg" 
script='initPicture(0,1,0)
marker = "dot"
a=text([0,1],"a",aboveleft)
b=text([1,1],"b",aboveright)
c=text([1,0],"c",right)
d=text([0,0],"d",left)
path([a,b,c,d,a,c,d,b])
text([.5,0],"K4",below)'
/graph


    math/ x_12 = - ( p / 2) +- sqrt {: (p/2)^2 - q :} /math

Postfilters
--------------------

### Code

    [lang:php]
    function callreplacefn($matches)
    {    
        $list = explode(" ", $matches[1]);
        $fn = $list[0];
        unset($list[0]);
        if(function_exists($fn))
            return call_user_func_array($fn, $list);
        else
            return $matches[0];
    }


Anderer Code

    [lang:python]
    #!/usr/bin/python -OOOO
    # -*- coding: utf-8 -*-

    import readline, sys, os
    import optparse

    __author__="Alexander Weigl <alexweigl@gmail.com>"
    __version__="0.1"
    __slots__=[]

    def _input_default(prompt, default): 
        def startup_hook(): 
            readline.insert_text(default) 
        readline.set_startup_hook(startup_hook) 
        try:     return raw_input(prompt) 
        finally: readline.set_startup_hook(None) 

    def __get_options():
    parser = optparse.OptionParser()

    parser.add_option("-n", "--name-only", 
            help="edit only the base name", dest="nameOnly", action="store_true", default=False)

    parser.add_option("-v", "--verbose", 
            help="verbose output", dest="verbose", action="store_true", default=False)

    return parser.parse_args()

    if __name__=='__main__':
    opts, args = __get_options()

    for filnm in args:
        absfilnm = os.path.abspath(filnm)

        print "File: %s" % absfilnm

        if opts.nameOnly:
        new_name = _input_default("New Name: ", default=os.path.basename(absfilnm))
        new_name =os.path.join(
                    os.path.dirname(absfilnm) , 
                    new_name)
        else:
        new_name = _input_default("New Path: ", default=absfilnm )
        
        if opts.verbose: 
            print "move %s --> %s " %(absfilnm, new_name) 

        os.rename(absfilenm, new_name)
