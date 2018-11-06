<?php 
/*
 * Método de conexão sem padrões
 */
 
try {
	$conn1 = new PDO('sqlite:base_original');
	$conn2 = new PDO('sqlite:base_nova');
	
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # DIM_LOJA
	$result_dim_loja = $conn1->query('SELECT l.id as "id", 
												l.nome as "nome", 
												l.endereco as "endereco", 
												l.telefone as "telefone",
												(SELECT c.nome FROM cidade c WHERE c.id=l.cidade_id LIMIT 1) as "nome_cidade",
												(SELECT e.nome FROM estado e INNER JOIN cidade c ON(e.id = c.estado_id)
												WHERE c.id=l.cidade_id LIMIT 1) as "nome_estado"
										FROM loja l');
		
    foreach($result_dim_loja as $row) {
		$id      	 = $row['id'];
		$nome 		 = $row['nome'];
		$endereco    = $row['endereco'];
		$nome_cidade = $row['nome_cidade'];
		$nome_estado = $row['nome_estado'];
		
		$sub_result = $conn2->query("SELECT count(*) as 'count' from dim_loja where id = '{$id}'");
		$row = $sub_result->fetch();
		if ($row['count'] == 0)
		{
			$conn2->exec("INSERT INTO dim_loja(id,nome,endereco, telefone, cidade, estado)
								 VALUES ($id, '{$nome}', '{$endereco}', '{$telefone}', '{$nome_cidade}', '{$nome_estado}')");
								 echo '<br>DIM LOJA';
		}
		echo '<br> DIM_LOJA ';
    }
	
	
	
	
	# DIM_CLIENTE
	$result_dim_cliente    = $conn1->query('SELECT c.id as "id", 
												c.nome as "nome", 
												c.endereco as "endereco", 
												c.data_nascimento as "nascimento",
												c.sexo as "sexo",
												(SELECT cc.nome FROM cidade cc WHERE cc.id=c.cidade_id LIMIT 1) as "nome_cidade",
												(SELECT e.nome FROM estado e INNER JOIN cidade c ON(e.id = c.estado_id)
												WHERE e.id=c.estado_id LIMIT 1) as "nome_estado"
										FROM cliente c');
    foreach($result_dim_cliente as $row) {
		$id      	= $row['id'];
		$nome 		= $row['nome'];
		$endereco  	= $row['endereco'];
		$nascimento = $row['nascimento'];
		$sexo       = $row['sexo'];
		$nome_cidade = $row['nome_cidade'];
		$nome_estado = $row['nome_estado'];
		
		$sub_result = $conn2->query("SELECT count(*) as 'count' from dim_cliente where id = '{$id}'");
		$row = $sub_result->fetch();
		if ($row['count'] == 0)
		{
			$conn2->exec("INSERT INTO dim_cliente(id,nome,endereco, sexo, cidade, estado, data_nascimento)
								 VALUES ($id, '{$nome}', '{$endereco}', '{$sexo}', '{$nome_cidade}', '{$nome_estado}', '{$data_nascimento}')");
								 echo '<br>DIM CLIENTE';
		}
		
		echo '<br> DIM_CLIENTE ';
    }
	
	# DIM_VENDEDOR
	$result_dim_vendedor = $conn1->query('SELECT v.id as "id", 
												v.nome as "nome", 
												(SELECT c.nome FROM cidade c WHERE c.id=v.cidade_id LIMIT 1) as "nome_cidade",
												(SELECT e.nome FROM estado e INNER JOIN cidade c ON(e.id = c.estado_id)
												WHERE e.id=c.estado_id LIMIT 1) as "nome_estado",
												(SELECT l.nome FROM loja l WHERE l.id=v.loja_id LIMIT 1) as "nome_loja"
										FROM vendedor v;');
    foreach($result_dim_vendedor as $row) {
		$id      	 = $row['id'];
		$nome 		 = $row['nome'];
		$nome_cidade = $row['nome_cidade'];
		$nome_estado = $row['nome_estado'];
		$nome_loja   = $row['nome_loja'];
		
		$sub_result = $conn2->query("SELECT count(*) as 'count' from dim_vendedor where id = '{$id}'");
		$row = $sub_result->fetch();
		if ($row['count'] == 0)
		{
			$conn2->exec("INSERT INTO dim_vendedor(id,nome, cidade, estado, loja) VALUES ($id, '{$nome}', '{$nome_cidade}', '{$nome_estado}', '{$nome_loja}')");
								 echo '<br>DIM VENDEDOR';
		}
		echo '<br> DIM_VENDEDOR ';
    }
	
	# DIM_PRODUTO
	$result_dim_produto = $conn1->query('SELECT p.id as "id", 
												p.nome as "nome", 
												p.unidade as "unidade", 
												p.valor_custo as "custo", 
												p.preco as "preco", 
												p.estoque as "estoque",
												(SELECT c.nome FROM categoria c WHERE c.id=p.categoria_id LIMIT 1) as "categoria",
												(SELECT m.nome FROM marca m WHERE m.id=p.marca_id LIMIT 1) as "marca"
										FROM produto p');
	
	foreach($result_dim_produto as $row) {
		$id      	= $row['id'];
		$nome       = $row['nome'];
		$unidade  	= $row['unidade'];
		$custo  	= $row['custo'];
		$preco  	= $row['preco'];
		$estoque  	= $row['estoque'];
		$categoria  = $row['categoria'];
		$marca      = $row['marca'];
		
		$sub_result = $conn2->query("SELECT count(*) as 'count' 
									FROM dim_produto
									WHERE id = '{$id}' AND '{$id}' ||'*'|| '{$nome}' ||'*'|| '{$categoria}' ||'*'|| '{$marca}' 
									      NOT IN (SELECT dp.id_antigo ||'*'|| dp.nome ||'*'|| dp.categoria ||'*'|| dp.marca FROM dim_produto dp)");
		$row = $sub_result->fetch();
		if ($row['count'] == 0)
		{			
			$conn2->exec("INSERT INTO dim_produto(id_antigo, nome, unidade, valor_custo, preco, categoria, estoque, marca)
								 VALUES ($id, '{$nome}', '{$unidade}', $custo, $preco, '{$categoria}', $estoque, '{$marca}')");
		}
		echo '<br> DIM_PRODUTO ';
    }
	
	# FAT VENDA
	$result_fat_venda = $conn1->query('SELECT 	v.data_venda as "data", 
														v.loja_id as "loja_id", 
														v.cliente_id as "cliente_id",
														v.vendedor_id as "vendedor_id",
														pv.produto_id as "produto_id", 
														pv.quantidade as "quantidade", 
														pv.valor as "valor"
												FROM venda v
												INNER JOIN produto_venda pv ON(v.id = pv.venda_id) ');
 
	foreach ($result_fat_venda as $row)
	{
		$data			= $row['data'];	
		$loja_id        = $row['loja_id'];
		$cliente_id		= $row['cliente_id'];
		$vendedor_id	= $row['vendedor_id'];		
		$produto_id 	= $row['produto_id'];
				
		$quantidade	    = $row['quantidade'];
		$valor		    = $row['valor'];
		echo $data;
		$conn2->exec("INSERT INTO fat_venda (dim_vendedor_id, dim_tempo_id, dim_cliente_id,
											 dim_produto_id, dim_loja_id, quantidade, valor)
									VALUES (
									(SELECT max(id) FROM dim_vendedor WHERE id={$vendedor_id}),
									(SELECT max(id) FROM dim_tempo WHERE data='{$data}'),  
									(SELECT max(id) FROM dim_cliente WHERE id={$cliente_id}),
									(SELECT max(id_antigo) FROM dim_produto WHERE id_antigo={$produto_id}),   
									(SELECT max(id) FROM dim_loja WHERE id={$loja_id}),
									$quantidade, $valor)");	
		echo '<br> FATs ORDEM PRODUÇÃO ';
	}	
	
} 
catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
//$conn2->commit();