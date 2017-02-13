<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUriruisf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uriruisf', function (Blueprint $table) {
            $table->increments('id');
            $table = $this->columns($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uriruisf');
    }

    public function columns($table)
    {
        $table->integer('syozok')      ->comment('SYOZOK 所属CD'); // 
        $table->integer('tokuno')      ->comment('TOKUNO 得意先CD'); // 
        $table->integer('knrino')      ->comment('KNRINO 管理NO'); // 
        $table->integer('knrnor')      ->comment('KNRNOR 行NO'); // 
        $table->char   ('syaten', 6)   ->comment('SYATEN 社店CD'); // 
        $table->date   ('syoymd')      ->comment('SYOYMD 処理日'); // 
        $table->date   ('uriymd')      ->comment('URIYMD 計上日'); // 
        $table->date   ('denymd')      ->comment('DENYMD 伝票日'); // 
        $table->integer('denno' )      ->comment('DENNO  伝票NO.'); // 
        $table->integer('gyono' )      ->comment('GYONO  伝票行NO'); // 
        $table->integer('rethu' )      ->comment('RETHU  伝票列NO'); // 
        $table->integer('bin'   )      ->comment('BIN    便'); // 
        $table->integer('shcds' )      ->comment('SHCDS  商品CD'); // 
        $table->string ('shcdt' , 8)   ->comment('SHCDT  相手商品CD'); // 
        $table->string ('shnm1' , 20)  ->comment('SHNM1  商品名１'); // 
        $table->string ('shnm2' , 20)  ->comment('SHNM2  商品名２'); // 
        $table->char   ('densyu', 2)   ->comment('DENSYU 伝種'); // 
        $table->char   ('denku' , 2)   ->comment('DENKU  伝区'); // 
        $table->date   ('motymd')      ->comment('MOTYMD 元伝票日'); // 
        $table->integer('motdno')      ->comment('MOTDNO 元伝票NO'); // 
        $table->char   ('akaryu', 1)   ->comment('AKARYU 赤理由区分'); // 
        $table->char   ('bunrui', 4)   ->comment('BUNRUI 分類CD'); // 
        $table->char   ('uriku' , 1)   ->comment('URIKU  売区'); // 
        $table->char   ('jtani' , 1)   ->comment('JTANI  受注単位'); // 
        $table->decimal('jirisu', 5)   ->comment('JIRISU 受注入数'); // 
        $table->decimal('jkosu' , 5, 2)->comment('JKOSU  受注個数'); // 
        $table->decimal('jsuryo', 7, 2)->comment('JSURYO 受注数量'); // 
        $table->decimal('jjyury', 7, 2)->comment('JJYURY 受注重量'); // 
        $table->decimal('jtanka', 7, 2)->comment('JTANKA 受注単価'); // 
        $table->decimal('jkin'  , 9)   ->comment('JKIN   受注金額'); // 
        $table->char   ('utani' , 1)   ->comment('UTANI  単位'); // 
        $table->decimal('uirisu', 5)   ->comment('UIRISU 売上入数'); // 
        $table->decimal('ukosu' , 5, 2)->comment('UKOSU  売上個数'); // 
        $table->decimal('usuryo', 7, 2)->comment('USURYO 売上数量'); // 
        $table->decimal('ujyury', 7, 2)->comment('UJYURY 売上重量'); // 
        $table->decimal('utanka', 7, 2)->comment('UTANKA 売上単価'); // 
        $table->decimal('ukin'  , 9)   ->comment('UKIN   売上金額'); // 
        $table->decimal('baika' , 7)   ->comment('BAIKA  売価'); // 
        $table->decimal('stan'  , 7)   ->comment('STAN   仕入単価'); // 
        $table->decimal('gbaika', 7)   ->comment('GBAIKA G売価'); // 
        $table->char   ('keitai', 1)   ->comment('KEITAI 形態'); // 
        $table->char   ('tokusc', 6)   ->comment('TOKUSC 取引先CD'); // 
        $table->integer('tanto' )      ->comment('TANTO  担当'); // 
        $table->integer('hachu' )      ->comment('HACHU  発注先CD'); // 
        $table->integer('shiren')      ->comment('SHIREN 仕入先CD'); // 
        $table->decimal('stanka', 7, 2)->comment('STANKA 仕入単価'); // 
        $table->integer('zuikno')      ->comment('ZUIKNO 随時入庫NO'); // 
        $table->char   ('tokecd', 10)  ->comment('TOKECD 統計CD'); // 
        $table->integer('cntno' )      ->comment('CNTNO  CNT番号'); // 
        $table->char   ('cntgyo', 2)   ->comment('CNTGYO CNT行'); // 
        $table->date   ('seikyu')      ->comment('SEIKYU 請求日'); // 
        $table->date   ('kaisyu')      ->comment('KAISYU 回収日'); // 
        $table->char   ('kaisyj', 1)   ->comment('KAISYJ 回収条件'); // 
        $table->char   ('flg15' , 5)   ->comment('FLG15  FLG1～5'); // 
        $table->char   ('izblno', 1)   ->comment('IZBLNO イズミヤBLOCK-NO'); // 
        $table->char   ('izblnn', 2)   ->comment('IZBLNN イズミヤBLOCK-NN'); // 
        $table->char   ('jancd' , 13)  ->comment('JANCD  JANコード'); // 
        $table->char   ('dmy'   , 10)  ->comment('DMY    ダミー'); // 
        $table->decimal('zkmtan', 7, 2)->comment('ZKMTAN 税込単価'); // 
        $table->decimal('zkmkin', 9)   ->comment('ZKMKIN 税込金額'); // 
        $table->char   ('tdenno', 10)  ->comment('TDENNO 相手伝票NO'); // 
        $table->char   ('butkbn', 1)   ->comment('BUTKBN 物流区分'); // 

        return $table;
    }
}
