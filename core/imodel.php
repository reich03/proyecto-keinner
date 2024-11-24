<?php
interface IModel
{

    public function save();
    public function update();
    public function delete();
    public function get($id);
    public function getAll();
    public function from($array);
}
