<fieldset>
    <legend>Información General</legend>

    <label for="titulo">Titulo:</label>
    <input type="text" id="titulo" name="propiedad[titulo]" placeholder="Titulo  de la propiedad" value="<?php echo s($propiedad->titulo); ?>">
    <label for="precio">precio:</label>
    <input type="number" id="precio" name="propiedad[precio]" placeholder="Precio  de la propiedad" value="<?php echo s($propiedad->precio); ?>">
    <label for="imagen">imagen:</label>
    <input type="file" id="imagen" name="propiedad[imagen]" accept="image/jpeg, image/png" >

    <?php if($propiedad->imagen){ ?>
        <img src="imagenes/<?php echo $propiedad->imagen ?>" class="imagen-small" alt="">
    <?php } ?>

    <label for="descripcion">descripcion:</label>
    <textarea id="descripcion" name="propiedad[descripcion]" placeholder="descripcion  de la propiedad"><?php echo s($propiedad->descripcion); ?></textarea>
</fieldset>
<fieldset>
    <legend>Informacion de la propiedad</legend>
    <label for="habitaciones">habitaciones:</label>
    <input type="number" min="1" max="9" id="habitaciones" name="propiedad[habitaciones]" placeholder="habitaciones  de la propiedad" value="<?php echo s($propiedad->habitaciones); ?>">
    <label for="wc">wc:</label>
    <input type="number" min="1" max="9" id="wc" name="propiedad[wc]" placeholder="wc  de la propiedad" value="<?php echo s($propiedad->wc); ?>">
    <label for="estacionamiento">estacionamientos:</label>
    <input type="number" min="1" max="9" id="estacionamiento" name="propiedad[estacionamiento]" placeholder="estacionamientos  de la propiedad" value="<?php echo s($propiedad->estacionamiento); ?>">
</fieldset>
<fieldset>
    <legend>Vendedor</legend>
    <label for="vendedor">Vendedor</label>
    <select name="propiedad[vendedorId]" id="vendedor">
        <option value="" selected>--Seleccione--</option>
        <?php foreach($vendedores as $vendedor) { ?>
            <option 
                <?php echo $propiedad->vendedorId === $vendedor->id ? 'selected' : ''; ?>
                value="<?php echo s($vendedor->id);  ?>"><?php echo s($vendedor->nombre) . " " . s($vendedor->apellido); ?></option>
        <?php } ?>
    </select>
</fieldset>