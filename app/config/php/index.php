<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paginacion</title>

    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/all.min.css">

</head>
<body>
    <main>
        <div class="container py-3 text-center">
            <h2>Personajes</h2>
            
            <div class="row g-4">
                <div class="col-auto">
                    <label for="num_registros" class="col-form-label">Mostrar: </label>
                </div>
                <div class="col-auto">
                    <select name="num_registros" id="num_registros" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>    
                </div>
                <div class="col-auto">
                    <label for="num_registros" class="col-form-label">registros </label>
                </div>
                <div class="col-6"></div>
                <div class="col-auto">
                    <label for="campo" class="col-form-label">Buscar: </label>
                </div>
                <div class="col-auto">
                    <input type="text" name="campo" id="campo" class="form-control">
                </div>
            </div>
        </div>
        <div class="container py-4 text-center">
            <div class="col">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="sort asc">ID</th>
                            <th class="sort asc">Nombre</th>
                            <th class="sort asc">Elemento</th>
                            <th class="sort asc">Nacion</th>
                            <th class="sort asc">Arma</th>
                            <th class="sort asc">Version de ingreso</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="content">
                        
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-6">
                    <label id="lbl-total"></label>
                </div>
                <div class="col-6" id="nav-pagina">

                </div>
                <input type="hidden" id="pagina" value="1">
                <input type="hidden" id="orderCol" value="0">
                <input type="hidden" id="orderType" value="asc">
            </div>
        </div>
    </main>

    <script>
        getData()

        document.getElementById("campo").addEventListener("keyup" , function(){
            getData()
        }, false)
        document.getElementById("num_registros").addEventListener("change" , function(){
            getData()
        }, false)
        
        function getData(){
            let input = document.getElementById("campo").value
            let num_registros = document.getElementById("num_registros").value
            let content = document.getElementById("content")
            let pagina = document.getElementById("pagina").value
            let orderCol = document.getElementById("orderCol").value
            let orderType = document.getElementById("orderType").value

            if(pagina == null){
                pagina = 1
            }

            let url = "load.php"
            let formData = new FormData()
            formData.append('campo', input)
            formData.append('registros', num_registros)
            formData.append('pagina', pagina)
            formData.append('orderCol', orderCol)
            formData.append('orderType', orderType)

            fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {
                content.innerHTML = data.data
                document.getElementById("lbl-total").innerHTML = 'Mostrando ' + data.filtro + ' de ' + data.total + ' registros'
                document.getElementById("nav-pagina").innerHTML = data.paginacion
            }).catch(err => console.log(err))
        }

        function nextPage(pagina){
            document.getElementById('pagina').value = pagina
            getData()
        }

        let columns = document.getElementsByClassName("sort")
        let tamano = columns.length
        for(let i = 0 ; i < tamano ; i++){
            columns[i].addEventListener("click", ordenar)
        }

        function ordenar(e){
            let elemento = e.target

            document.getElementById('orderCol').value = elemento.cellIndex

            if(elemento.classList.contains("asc")){
                document.getElementById("orderType").value = "asc"
                elemento.classList.remove("asc")
                elemento.classList.add("desc")
            } else {
                document.getElementById("orderType").value = "desc"
                elemento.classList.remove("desc")
                elemento.classList.add("asc")
            }

            getData()
        }

    </script>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>


