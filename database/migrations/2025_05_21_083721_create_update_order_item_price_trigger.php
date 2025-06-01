<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE or replace FUNCTION update_order_item_price_func() RETURNS TRIGGER AS $$
            DECLARE discount_percentage DECIMAL(10,2);
            BEGIN
                -- Получаем максимальную процентную скидку для товара
                SELECT MAX(d.discount_value) INTO discount_percentage
                FROM discounts d
                JOIN product_discounts pd ON pd.discount_id = d.discount_id
                WHERE pd.product_id = NEW.product_id
                AND d.is_active = TRUE
                AND d.start_date <= NOW()
                AND d.end_date >= NOW();

                -- Если у пользователя есть персональная скидка, применяем её
                IF EXISTS (SELECT 1 FROM orders WHERE orders.user_id = NEW.user_id AND orders.status = '.ordered.') THEN
                    SELECT MAX(d.discount_value) INTO discount_percentage
                    FROM discounts d
                    JOIN user_discounts ud ON ud.discount_id = d.discount_id
                    WHERE ud.user_id = NEW.user_id
                    AND d.is_active = TRUE
                    AND d.start_date <= NOW()
                    AND d.end_date >= NOW();
                END IF;

                -- Если скидка найдена, пересчитываем цену
                IF discount_percentage IS NOT NULL THEN
                    NEW.price := NEW.price * (100 - discount_percentage / 100);
                END IF;

                -- Обновляем цену в Order_Items ТОЛЬКО если заказ НЕ "ordered"
                UPDATE order_items
                SET price = NEW.price
                WHERE product_id = NEW.product_id
                AND order_id IN (SELECT order_id FROM orders WHERE status != '.ordered.');

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;


            -- Создаём функцию для триггера
            CREATE OR REPLACE FUNCTION apply_discount_on_insert()
            RETURNS TRIGGER AS $$
            DECLARE 
                discount_percentage DECIMAL(10,2) := 0;
                old_price DECIMAL(10,2);
            BEGIN
                -- Получаем исходную цену товара
                SELECT price INTO old_price
                FROM products
                WHERE product_id = NEW.product_id
                LIMIT 1;

                -- Получаем максимальную скидку для товара
                SELECT COALESCE(MAX(d.discount_value), 0) INTO discount_percentage
                FROM discounts d
                JOIN product_discounts pd ON pd.discount_id = d.discount_id
                WHERE pd.product_id = NEW.product_id
                AND d.is_active = TRUE
                AND d.start_date <= NOW()
                AND d.end_date >= NOW();

                -- Корректное уменьшение цены на % скидки
                IF discount_percentage > 0 AND discount_percentage <= 100 THEN
                    NEW.price := old_price * (1 - (discount_percentage / 100));
                ELSE
                    NEW.price := old_price;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            -- Создаём триггер, который срабатывает при вставке в order_items
            CREATE OR REPLACE TRIGGER apply_discount_on_insert_trigger
            BEFORE INSERT ON order_items
            FOR EACH ROW
            EXECUTE FUNCTION apply_discount_on_insert();


            -- Создаём триггер, который срабатывает при вставке новой записи в order_items
            CREATE OR REPLACE TRIGGER apply_discount_on_insert_trigger
            BEFORE INSERT ON order_items
            FOR EACH ROW
            EXECUTE FUNCTION apply_discount_on_insert();

        ');
    }

    public function down()
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS update_order_item_price ON products;
            DROP FUNCTION IF EXISTS update_order_item_price_func;
            DROP TRIGGER IF EXISTS apply_discount_on_insert_trigger ON order_items;
            DROP FUNCTION IF EXISTS apply_discount_on_insert;
        ');
    }
};


/*
CREATE or replace FUNCTION update_order_item_price_func() RETURNS TRIGGER AS $$
            DECLARE discount_percentage DECIMAL(10,2);
            BEGIN
                -- Получаем максимальную процентную скидку для товара
                SELECT MAX(d.discount_value) INTO discount_percentage
                FROM discounts d
                JOIN product_discounts pd ON pd.discount_id = d.discount_id
                WHERE pd.product_id = NEW.product_id
                AND d.is_active = TRUE
                AND d.start_date <= NOW()
                AND d.end_date >= NOW();

                -- Если у пользователя есть персональная скидка, применяем её
                IF EXISTS (SELECT 1 FROM orders WHERE orders.user_id = NEW.user_id AND orders.status = '.ordered.') THEN
                    SELECT MAX(d.discount_value) INTO discount_percentage
                    FROM discounts d
                    JOIN user_discounts ud ON ud.discount_id = d.discount_id
                    WHERE ud.user_id = NEW.user_id
                    AND d.is_active = TRUE
                    AND d.start_date <= NOW()
                    AND d.end_date >= NOW();
                END IF;

                -- Если скидка найдена, пересчитываем цену
                IF discount_percentage IS NOT NULL THEN
                    NEW.price := NEW.price * (100 - discount_percentage / 100);
                END IF;

                -- Обновляем цену в Order_Items ТОЛЬКО если заказ НЕ "ordered"
                UPDATE order_items
                SET price = NEW.price
                WHERE product_id = NEW.product_id
                AND order_id IN (SELECT order_id FROM orders WHERE status != '.ordered.');

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
            */