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

    let text;
    if(obj.id == root_id){
        text = document.createTextNode('id='+ obj.id);
        div.appendChild(text);
    } else {
        text = document.createTextNode('id=');
        div.appendChild(text);
        let a=document.createElement('a');
        a.href='/tree?id='+obj.id;
        a=div.appendChild(a);
        a.appendChild(document.createTextNode(obj.id));
    }

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

    if (obj.id != root_id) {

        input = document.createElement('input');
        input.type = 'button';
        input.value = '-';
        input = div.appendChild(input);

        input.onclick = function () {
            if( confirm('Будут удалены все потомки этого узла вместе с ним. Продолжить?')){

                removeNode('/tree', { id: obj.id, _token: _token, _method:"DELETE" })
                    .then((data) => {
                        location.href='/tree?id='+root_id;
                    });
            }
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

    let text = document.createTextNode('position:');
    form.appendChild(text);

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

async function removeNode(url = '', data = {}) {
    const response = await fetch(url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });
    return await response.json();
}
