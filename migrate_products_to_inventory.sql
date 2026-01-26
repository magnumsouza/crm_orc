-- Migra products -> inventory e atualiza quote_items para inventory_id.
-- Rode este script APÓS atualizar o código.

START TRANSACTION;

-- 1) Copiar produtos para inventory (gera SKU com base no id do produto).
INSERT INTO inventory (sku, name, category, description, quantity, price, cost, min_quantity, max_quantity, status)
SELECT
    CONCAT('PROD-', id) AS sku,
    name,
    '' AS category,
    description,
    0 AS quantity,
    price,
    0 AS cost,
    5 AS min_quantity,
    100 AS max_quantity,
    'Ativo' AS status
FROM products
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    description = VALUES(description),
    price = VALUES(price),
    status = VALUES(status);

-- 2) Adicionar inventory_id em quote_items (se ainda não existir).
ALTER TABLE quote_items ADD COLUMN inventory_id INT NULL AFTER quote_id;

-- 3) Relacionar quote_items com inventory usando o SKU gerado.
UPDATE quote_items qi
JOIN inventory i ON i.sku = CONCAT('PROD-', qi.product_id)
SET qi.inventory_id = i.id
WHERE qi.inventory_id IS NULL;

-- 4) Tornar inventory_id obrigatório e criar FK.
ALTER TABLE quote_items
    MODIFY inventory_id INT NOT NULL,
    ADD CONSTRAINT fk_quote_items_inventory
        FOREIGN KEY (inventory_id) REFERENCES inventory(id) ON DELETE RESTRICT;

-- 5) Remover FK antiga e coluna product_id.
ALTER TABLE quote_items DROP FOREIGN KEY quote_items_ibfk_2;
ALTER TABLE quote_items DROP COLUMN product_id;

-- 6) (Opcional) Remover tabela products.
DROP TABLE products;

COMMIT;
