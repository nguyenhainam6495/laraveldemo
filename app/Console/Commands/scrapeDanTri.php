<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
use App\TinTuc;
class scrapeDanTri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:dantri';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $category = [
        'kinh-doanh.htm',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->category as $category) {
            // print("lay cua danh muc " . $category . "\n");
            $l = env("DAN_TRI")."/".$category;
            // print($l . "\n");
            $crawler = Goutte::request('GET', $l); 
            $linkTinTuc = $crawler->filter('a.fon6')->each(function ($node) {
                  return $node->attr("href");
                });
            foreach ($linkTinTuc as $link) {
                $l = env("DAN_TRI").$link;
                print($l . "\n");
                self::scrapeTinTuc($l);
           }
       }

    }

    public static function scrapeTinTuc($url)
    {
        $crawler = Goutte::request('GET', $url);
            
        $data=array();
            $data = [
            'TieuDe' => "",
            'TieuDeKhongDau' => "", 
            'NoiDung' => "",
            'TomTat' => "",
            'Hinh' => "",
            ];
        $TieuDe = $crawler->filter('h1.fon31.mgb15')->each(function ($node) {
              return $node->text();
            });


        if(isset($TieuDe[0])){
            $TieuDe = $TieuDe[0];
            $data['TieuDe']=$TieuDe;
        }

        $TieuDeKhongDau = str_slug($TieuDe);
        $data['TieuDeKhongDau'] = $TieuDeKhongDau;


        $TomTat = $crawler->filter('h2.fon33.mt1.sapo')->each(function ($node) {
              return $node->text();
            });
  

        if(isset($TomTat[0])){
            $TomTat = $TomTat[0];
            $data['TomTat']=$TomTat;
        }

        $TomTat = str_replace('Dân trí','', $TomTat);
 
        $NoiDung = $crawler->filter('h2.fon33.mt1.sapo')->each(function ($node) {
              return $node->text();
            });


        if(isset($NoiDung[0])){
            $NoiDung = $NoiDung[0];
            $data['NoiDung']=$NoiDung;
        }

        $Hinh = $crawler->filter('div#divNewsContent.detail-content .VCSortableInPreviewMode img')->each(function ($node) {
              return $node->attr('src');
            });


        if(isset($Hinh[0])){
            $Hinh = $Hinh[0];
            $data['Hinh']=$Hinh;

        }
        
        $abc =TinTuc::where('TieuDeKhongDau',$data['TieuDeKhongDau'])->first();

        if(isset($abc)){
            print('chua luu \n' );
        }else{
            print('da luu \n' );
            TinTuc::create($data);
        }
        
    }
}
