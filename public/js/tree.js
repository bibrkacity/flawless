function render()
{
    let obj = JSON.parse(json);

    node( obj, width );

}


function node( obj, div_width )
{
    let div_outer = document.getElementById('node'+obj.id);

    let div = document.createElement('div');
    div.className = 'node';
    div=div_outer.appendChild(div);

    let text = document.createTextNode('id='+ obj.id);
    div.appendChild(text);

    let position;
    let input
    if (obj.children.length < 2) {
        input = document.createElement('input');
        input.type = 'button';
        input.value = '+';
        input = div.appendChild(input);

        position = obj.children.length == 1 ? 2 : 1;
        input.onclick = function () {
            renderAddForm(obj.id, div_outer, position);
        }
    }

    for(let i=0; i<obj.children.length; i++){
        let div = document.createElement('div');
        div.className = 'node_outer';
        if(obj.children[i].position == 1)
            div.className += ' left';
        else
            div.className += ' right';

        let w = Math.ceil(div_width/2);
        div.style.width = w +'px';
        div.id = 'node'+obj.children[i].id;
        div=div_outer.appendChild(div);
        node(obj.children[i],w);
    }

}


function renderAddForm(node_id,div_outer,position)
{
    let div = document.createElement('div');
    div.className = 'form';
    div=div_outer.appendChild(div);

    let form = document.createElement('FORM');
    form.method='post';
    form.action='/tree';
    form=div.appendChild(form);

    let input = document.createElement('input');
    input.type = 'hidden' ;
    input.name='_token';
    input.value=_token;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden' ;
    input.name='id';
    input.value=node_id;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'number' ;
    input.name='position';
    input.value=position;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'submit' ;
    input.value='add';
    form.appendChild(input);


}
