<?php
/**
 * Created by PhpStorm.
 * User: jssan
 * Date: 11/12/2018
 * Time: 11:54
 */
define('ROOT_PATH', dirname(__DIR__));

include_once ROOT_PATH . "/DatabaseManager.php";

class ProductDAO extends DatabaseManager {


    /**
     * ProductDAO constructor.
     */

    public function __construct() {
        parent::__construct();
    }

    function insert(Product $MODEL) {
        $stmt = $this->conn->prepare("INSERT INTO products(name, `desc`, price) VALUES (?,?,?)");
        $name = $MODEL->getName();
        $desc = $MODEL->getDesc();
        $price = $MODEL->getPrice();
        $stmt->bind_param('ssd', $name, $desc, $price);
        if ($stmt->execute()) {
            $MODEL->setCode($this->conn->insert_id);
            return $MODEL;
        } else {
            $t = date("d/m/y h:m:s");
            self::log("INSERT ON PRODUCTS ({$t}):");
            self::log($this->conn->error);
            return 0;
        }
    }

    function delete(Product $MODEL) {
        $id = $MODEL->getCode();
        $stmt = $this->conn->prepare("DELETE FROM products WHERE prodct_code=? ;");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            return $id;
        } else {
            $stmt->close();
            $t = date("d/m/y h:m:s");
            self::log("DELETE ON PRODUCTS ({$t}):");
            self::log($this->conn->error);
            return 0;
        }
    }

    function update(Product $MODEL) {
        $stmt = $this->conn->prepare("UPDATE products  SET `name`=? ,`desc`=?, price=? WHERE prodct_code=? ;");
        $id = $MODEL->getCode();
        $name = $MODEL->getName();
        $desc = $MODEL->getDesc();
        $price = $MODEL->getPrice();
        $stmt->bind_param('ssdi', $name, $desc, $price, $id);
        if ($stmt->execute()) {
            return $MODEL;
        } else {
            $t = date("d/m/y h:m:s");
            self::log("UPDATE ON PRODUCTS ({$t}):");
            self::log($this->conn->error);
            return 0;
        }
    }

    function selectAll() {
        $r = $this->conn->query("SELECT prodct_code as 'code', price,`desc`,name FROM products");
        if (!$r) {
            $t = date("d/m/y h:m:s");
            self::log("SELECT ON PRODUCTS ({$t}):");
            self::log($this->conn->error);
        }
        return $r;
    }

    function selectItem($id) {
        $r = $this->conn->query("SELECT prodct_code as 'code',`desc`,name, price FROM products WHERE prodct_code={$id}");
        if (!$r) {
            $t = date("d/m/y h:m:s");
            self::log("SELECT id ON PRODUCTS ({$t}):");
            self::log($this->conn->error);
        }
        return $r;
    }
}