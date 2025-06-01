<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoryTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*public function up()
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_category_type_on_insert_func()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Обновляем родительскую категорию
                UPDATE categories
                SET category_type = \'PARENT\'
                WHERE category_id = NEW.parent_category_id
                AND category_type = \'LEAF\';
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER update_category_type_on_insert
            AFTER INSERT ON categories
            FOR EACH ROW
            EXECUTE FUNCTION update_category_type_on_insert_func();

            -- Функция для предотвращения удаления ROOT категории
            CREATE OR REPLACE FUNCTION prevent_root_deletion_func()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.category_type = \'ROOT\' AND (
                    EXISTS (SELECT 1 FROM categories WHERE parent_category_id = OLD.category_id AND category_type = \'LEAF\')
                    OR (SELECT COUNT(*) FROM categories WHERE parent_category_id = OLD.category_id AND category_type = \'PARENT\') >= 2
                ) THEN
                    RAISE EXCEPTION \'Нельзя удалить ROOT категорию, пока есть LEAF или более одного PARENT\';
                END IF;
                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER prevent_root_deletion
            BEFORE DELETE ON categories
            FOR EACH ROW
            EXECUTE FUNCTION prevent_root_deletion_func();

            -- Функция для обновления PARENT на ROOT при удалении ROOT
            CREATE OR REPLACE FUNCTION update_parent_to_root_on_root_delete_func()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.category_type = \'ROOT\' AND 
                   (SELECT COUNT(*) FROM categories WHERE parent_category_id = OLD.category_id AND category_type = \'PARENT\') = 1 THEN
                    UPDATE categories
                    SET category_type = \'ROOT\', parent_category_id = NULL
                    WHERE parent_category_id = OLD.category_id;
                END IF;
                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER update_parent_to_root_on_root_delete
            AFTER DELETE ON categories
            FOR EACH ROW
            EXECUTE FUNCTION update_parent_to_root_on_root_delete_func();

            -- Функция для переназначения родителя при удалении PARENT без LEAF
            CREATE OR REPLACE FUNCTION reassign_parent_on_deletion_func()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.category_type = \'PARENT\' AND 
                   NOT EXISTS (SELECT 1 FROM categories WHERE parent_category_id = OLD.category_id AND category_type = \'LEAF\') THEN
                    UPDATE categories
                    SET parent_category_id = OLD.parent_category_id
                    WHERE parent_category_id = OLD.category_id;
                END IF;
                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER reassign_parent_on_deletion
            AFTER DELETE ON categories
            FOR EACH ROW
            EXECUTE FUNCTION reassign_parent_on_deletion_func();

            -- Функция для переназначения родителя LEAF при удалении PARENT
            CREATE OR REPLACE FUNCTION reassign_leaf_parent_on_parent_deletion_func()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.category_type = \'PARENT\' AND 
                   EXISTS (SELECT 1 FROM categories WHERE parent_category_id = OLD.category_id AND category_type = \'LEAF\') THEN
                    UPDATE categories
                    SET parent_category_id = OLD.parent_category_id
                    WHERE parent_category_id = OLD.category_id;
                END IF;
                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER reassign_leaf_parent_on_parent_deletion
            AFTER DELETE ON categories
            FOR EACH ROW
            EXECUTE FUNCTION reassign_leaf_parent_on_parent_deletion_func();
        ');
    }
*/
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    /*public function down()
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS update_category_type_on_insert ON categories;
            DROP TRIGGER IF EXISTS prevent_root_deletion ON categories;
            DROP TRIGGER IF EXISTS update_parent_to_root_on_root_delete ON categories;
            DROP TRIGGER IF EXISTS reassign_parent_on_deletion ON categories;
            DROP TRIGGER IF EXISTS reassign_leaf_parent_on_parent_deletion ON categories;
            
            DROP FUNCTION IF EXISTS update_category_type_on_insert_func();
            DROP FUNCTION IF EXISTS prevent_root_deletion_func();
            DROP FUNCTION IF EXISTS update_parent_to_root_on_root_delete_func();
            DROP FUNCTION IF EXISTS reassign_parent_on_deletion_func();
            DROP FUNCTION IF EXISTS reassign_leaf_parent_on_parent_deletion_func();
        ');
    }*/
}