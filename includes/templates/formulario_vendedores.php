<fieldset>
    <legend>Información General</legend>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="vendedor[nombre]" placeholder="Nombre vendedor" value="<?php echo s($vendedor->nombre); ?>">
    <label for="apellido">apellido:</label>
    <input type="text" id="apellido" name="vendedor[apellido]" placeholder="apellido vendedor" value="<?php echo s($vendedor->apellido); ?>">
    
</fieldset>
<fieldset>
    <legend>Informacion extra</legend>
    <label for="telefono">telefono:</label>
    <input type="text" id="telefono" name="vendedor[telefono]" placeholder="telefono vendedor" value="<?php echo s($vendedor->telefono); ?>">
</fieldset>