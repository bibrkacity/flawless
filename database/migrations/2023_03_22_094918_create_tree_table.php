<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/**
 * Binary tree
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tree', function (Blueprint $table) {
            $table->unsignedInteger('id',true);
            $table->unsignedInteger('parent_id')
                ->comment('идентификатор родителя');
            $table->unsignedTinyInteger('position')
                ->comment('позиция ячейки относительно родителя (1 ли 2), то есть слева или справа от родителя');
            $table->string('path',12288)
                ->comment('путь ячейки вида 1.3.8, где 8 это id текущей ячейки, а 3 и 1 - это родители ячейки снизу вверх.
                  https://gist.github.com/codedokode/10539720#4-materialized-path');
            $table->unsignedInteger('level')
                ->comment('уровень бинара, начиная от 1');

        });
        \DB::statement("ALTER TABLE `tree` COMMENT 'Бінарне дерево'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tree');
    }
};
