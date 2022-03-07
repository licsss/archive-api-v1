<?php

namespace Database\Seeders\Recipe;

use App\Models\Recipe\Recipe_category;
use App\Models\Recipe\Recipe_item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Item extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record=[
            [
                "category"=>[
                    'name'=>'ご飯',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"ご飯",
                        "description"=>""
                    ],
                    [
                        "name"=>"お餅",
                        "description"=>""
                    ],
                    [
                        "name"=>"白米",
                        "description"=>""
                    ],
                    [
                        "name"=>"玄米",
                        "description"=>""
                    ],
                    [
                        "name"=>"もち米",
                        "description"=>""
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'麺類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"うどん(生麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"うどん(乾麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"うどん(冷凍)",
                        "description"=>""
                    ],
                    [
                        "name"=>"日本そば(生麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"日本そば(乾麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"日本そば(冷凍)",
                        "description"=>""
                    ],
                    [
                        "name"=>"中華そば(生麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"中華そば(乾麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"焼きそば(生麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"焼きそば(乾麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"そうめん(乾麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"冷麦(乾麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"スパゲッティ(生麺)",
                        "description"=>""
                    ],
                    [
                        "name"=>"スパゲッティ(乾麺)",
                        "description"=>""
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'パン類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"食パン",
                        "description"=>""
                    ],
                    [
                        "name"=>"総菜パン",
                        "description"=>""
                    ],
                    [
                        "name"=>"菓子パン",
                        "description"=>""
                    ],
                    [
                        "name"=>"バケット",
                        "description"=>""
                    ],
                    [
                        "name"=>"コッペパン",
                        "description"=>""
                    ],
                    [
                        "name"=>"クロワッサン",
                        "description"=>""
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'小麦粉',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"薄力粉",
                        "description"=>""
                    ],
                    [
                        "name"=>"中力粉",
                        "description"=>""
                    ],
                    [
                        "name"=>"強力粉",
                        "description"=>""
                    ],
                    [
                        "name"=>"バイオレット",
                        "description"=>""
                    ],
                    [
                        "name"=>"スーパーバイオレット",
                        "description"=>""
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'肉類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"牛薄切り",
                        "description"=>""
                    ],
                    [
                        "name"=>"牛ステーキ",
                        "description"=>""
                    ],
                    [
                        "name"=>"牛ブロック",
                        "description"=>""
                    ],
                    [
                        "name"=>"豚薄切り",
                        "description"=>""
                    ],
                    [
                        "name"=>"豚ブロック",
                        "description"=>""
                    ],
                    [
                        "name"=>"鶏もも",
                        "description"=>""
                    ],
                    [
                        "name"=>"鶏むね",
                        "description"=>""
                    ],
                    [
                        "name"=>"鶏ささみ",
                        "description"=>""
                    ],
                    [
                        "name"=>"牛ひき肉",
                        "description"=>""
                    ],
                    [
                        "name"=>"豚ひき肉",
                        "description"=>""
                    ],
                    [
                        "name"=>"合いびき肉",
                        "description"=>""
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'野菜類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"キャベツ",
                        "description"=>"きゃべつ"
                    ],
                    [
                        "name"=>"レタス",
                        "description"=>"れたす"
                    ],
                    [
                        "name"=>"白菜",
                        "description"=>"はくさい"
                    ],
                    [
                        "name"=>"玉ねぎ",
                        "description"=>"たまねぎ"
                    ],
                    [
                        "name"=>"長ネギ",
                        "description"=>"ながねぎ"
                    ],
                    [
                        "name"=>"もやし",
                        "description"=>""
                    ],
                    [
                        "name"=>"ほうれん草",
                        "description"=>"ほうれんそう"
                    ],
                    [
                        "name"=>"小松菜",
                        "description"=>"こまつな"
                    ],
                    [
                        "name"=>"プチトマト",
                        "description"=>"ぷちとまと"
                    ],
                    [
                        "name"=>"トマト",
                        "description"=>"とまと"
                    ],
                    [
                        "name"=>"きゅうり",
                        "description"=>""
                    ],
                    [
                        "name"=>"なす",
                        "description"=>""
                    ],
                    [
                        "name"=>"ピーマン",
                        "description"=>"ぴーまん"
                    ],
                    [
                        "name"=>"パプリカ",
                        "description"=>"ぱぷりか"
                    ],
                    [
                        "name"=>"かぼちゃ",
                        "description"=>""
                    ],
                    [
                        "name"=>"人参",
                        "description"=>"にんじん"
                    ],
                    [
                        "name"=>"大根",
                        "description"=>"だいこん"
                    ],
                    [
                        "name"=>"生姜",
                        "description"=>"しょうが"
                    ],
                    [
                        "name"=>"レンコン",
                        "description"=>"れんこん"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'果物類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"リンゴ",
                        "description"=>"りんご"
                    ],
                    [
                        "name"=>"イチゴ",
                        "description"=>"いちご"
                    ],
                    [
                        "name"=>"キウイ",
                        "description"=>"きうい"
                    ],
                    [
                        "name"=>"ミカン",
                        "description"=>"みかん"
                    ],
                    [
                        "name"=>"オレンジ",
                        "description"=>"おれんじ"
                    ],
                    [
                        "name"=>"ブルーベリー",
                        "description"=>"ぶるーべりー"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'魚介類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"鮭・サーモン",
                        "description"=>"さけ"
                    ],
                    [
                        "name"=>"ぶり",
                        "description"=>""
                    ],
                    [
                        "name"=>"さば",
                        "description"=>""
                    ],
                    [
                        "name"=>"さんま",
                        "description"=>""
                    ],
                    [
                        "name"=>"しらす",
                        "description"=>""
                    ],
                    [
                        "name"=>"えび",
                        "description"=>""
                    ],
                    [
                        "name"=>"かに",
                        "description"=>""
                    ],
                    [
                        "name"=>"いか",
                        "description"=>""
                    ],
                    [
                        "name"=>"たこ",
                        "description"=>""
                    ],
                    [
                        "name"=>"あさり",
                        "description"=>""
                    ],
                    [
                        "name"=>"牡蠣",
                        "description"=>"かき"
                    ],
                    [
                        "name"=>"ししゃも",
                        "description"=>""
                    ],
                    [
                        "name"=>"いくら",
                        "description"=>""
                    ],
                    [
                        "name"=>"数の子",
                        "description"=>"かずのこ"
                    ],
                    [
                        "name"=>"刺身",
                        "description"=>"さしみ"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'乾物・海藻類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"味のり",
                        "description"=>"あじのり"
                    ],
                    [
                        "name"=>"焼きのり",
                        "description"=>"やきのり"
                    ],
                    [
                        "name"=>"韓国のり",
                        "description"=>"かんこくのり"
                    ],
                    [
                        "name"=>"いりこ",
                        "description"=>""
                    ],
                    [
                        "name"=>"カツオ節",
                        "description"=>"かつおぶし"
                    ],
                    [
                        "name"=>"昆布",
                        "description"=>"こんぶ"
                    ],
                    [
                        "name"=>"乾燥わかめ",
                        "description"=>"かんそうわかめ"
                    ],
                    [
                        "name"=>"麩",
                        "description"=>"ふ"
                    ],
                    [
                        "name"=>"春雨",
                        "description"=>"はるさめ"
                    ],
                    [
                        "name"=>"葛切り",
                        "description"=>"くずきり"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'きのこ・山菜類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"しいたけ",
                        "description"=>""
                    ],
                    [
                        "name"=>"しめじ",
                        "description"=>""
                    ],
                    [
                        "name"=>"エリンギ",
                        "description"=>"えりんぎ"
                    ],
                    [
                        "name"=>"えのきたけ",
                        "description"=>""
                    ],
                    [
                        "name"=>"まいたけ",
                        "description"=>""
                    ],
                    [
                        "name"=>"松茸",
                        "description"=>"まつたけ"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'卵類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"卵",
                        "description"=>"たまご"
                    ],
                    [
                        "name"=>"卵(S)",
                        "description"=>"たまご"
                    ],
                    [
                        "name"=>"卵(M)",
                        "description"=>"たまご"
                    ],
                    [
                        "name"=>"卵(L)",
                        "description"=>"たまご"
                    ],
                    [
                        "name"=>"うずらの卵",
                        "description"=>"うずらのたまご"
                    ],
                    [
                        "name"=>"ゆで卵",
                        "description"=>"ゆでたまご"
                    ],
                    [
                        "name"=>"温泉卵",
                        "description"=>"おんせんたまご"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'いも類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"じゃがいも",
                        "description"=>""
                    ],
                    [
                        "name"=>"さつまいも",
                        "description"=>""
                    ],
                    [
                        "name"=>"里芋",
                        "description"=>"さといも"
                    ],
                    [
                        "name"=>"長いも",
                        "description"=>"ながいも"
                    ],
                    [
                        "name"=>"山芋",
                        "description"=>"やまいも"
                    ],
                    [
                        "name"=>"こんにゃく",
                        "description"=>""
                    ],
                    [
                        "name"=>"慈姑",
                        "description"=>"くわい"
                    ],
                    [
                        "name"=>"ばれいしょ",
                        "description"=>""
                    ],
                    [
                        "name"=>"インカのめざめ",
                        "description"=>""
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'乳製品類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"牛乳",
                        "description"=>"ぎゅうにゅう"
                    ],
                    [
                        "name"=>"ピザ用チーズ",
                        "description"=>"ぴざようちーず"
                    ],
                    [
                        "name"=>"粉チーズ",
                        "description"=>"こなちーず"
                    ],
                    [
                        "name"=>"スライスチーズ",
                        "description"=>"すらいすちーず"
                    ],
                    [
                        "name"=>"ベビーチーズ",
                        "description"=>"べびーちーず"
                    ],
                    [
                        "name"=>"クリームチーズ",
                        "description"=>"くりーむちーず"
                    ],
                    [
                        "name"=>"マスカルポーネチーズ",
                        "description"=>"ますかるぽーねちーず"
                    ],
                    [
                        "name"=>"ヨーグルト",
                        "description"=>"よーぐると"
                    ],
                    [
                        "name"=>"ホイップクリーム(植物性)",
                        "description"=>"ほいっぷくりーむ，しょくぶつせい"
                    ],
                    [
                        "name"=>"純乳脂(動物性)",
                        "description"=>"じゅんにゅうし，どうぶつせい"
                    ],
                    [
                        "name"=>"生クリーム(動物性)",
                        "description"=>"なまくりーむ，どうぶつせい"
                    ],
                    [
                        "name"=>"無塩バター",
                        "description"=>"むえんばたー"
                    ],
                    [
                        "name"=>"有塩バター",
                        "description"=>"ゆうえんばたー"
                    ],
                    [
                        "name"=>"発酵バター",
                        "description"=>"はっこうばたー"
                    ],
                    [
                        "name"=>"マーガリン",
                        "description"=>"まーがりん"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'豆類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"木綿豆腐",
                        "description"=>"もめんどうふ"
                    ],
                    [
                        "name"=>"絹豆腐",
                        "description"=>"きぬどうふ"
                    ],
                    [
                        "name"=>"油揚げ",
                        "description"=>"あぶらあげ"
                    ],
                    [
                        "name"=>"厚揚げ",
                        "description"=>"あつあげ"
                    ],
                    [
                        "name"=>"納豆",
                        "description"=>"なっとう"
                    ],
                    [
                        "name"=>"味噌",
                        "description"=>"みそ"
                    ],
                    [
                        "name"=>"豆乳",
                        "description"=>"とうにゅう"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'加工食品',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"ハム",
                        "description"=>"はむ"
                    ],
                    [
                        "name"=>"ウインナー",
                        "description"=>"ういんなー"
                    ],
                    [
                        "name"=>"ソーセージ",
                        "description"=>"そーせーじ"
                    ],
                    [
                        "name"=>"ベーコン",
                        "description"=>"べーこん"
                    ],
                    [
                        "name"=>"ジャム",
                        "description"=>"じゃむ"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'缶類',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"ツナ缶",
                        "description"=>"つなかん"
                    ],
                    [
                        "name"=>"さば缶",
                        "description"=>"さばかん"
                    ],
                    [
                        "name"=>"コーン缶",
                        "description"=>""
                    ],
                    [
                        "name"=>"トマト缶",
                        "description"=>"とまとかん"
                    ],
                    [
                        "name"=>"ミカン缶",
                        "description"=>"みかんかん"
                    ],
                    [
                        "name"=>"ミックスフルーツ缶",
                        "description"=>"みっくすふるーつかん"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'調味料',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"しょうゆ",
                        "description"=>""
                    ],
                    [
                        "name"=>"みりん",
                        "description"=>""
                    ],
                    [
                        "name"=>"料理酒",
                        "description"=>"りょうりしゅ"
                    ],
                    [
                        "name"=>"砂糖",
                        "description"=>"さとう"
                    ],
                    [
                        "name"=>"塩",
                        "description"=>"しお"
                    ],
                    [
                        "name"=>"塩コショウ",
                        "description"=>"しおこしょう"
                    ],
                    [
                        "name"=>"コショウ",
                        "description"=>"こしょう"
                    ],
                    [
                        "name"=>"酢",
                        "description"=>"す"
                    ],
                    [
                        "name"=>"味噌",
                        "description"=>"みそ"
                    ],
                    [
                        "name"=>"出汁",
                        "description"=>"だし"
                    ],
                    [
                        "name"=>"顆粒だし",
                        "description"=>"かりゅうだし"
                    ],
                    [
                        "name"=>"粉末だし",
                        "description"=>"ふんまつだし"
                    ],
                    [
                        "name"=>"うまみだし",
                        "description"=>""
                    ],
                    [
                        "name"=>"タンサン",
                        "description"=>"たんさん"
                    ],
                    [
                        "name"=>"粗びきコショウ",
                        "description"=>"あらびきこしょう"
                    ],
                    [
                        "name"=>"コショウ(ホール)",
                        "description"=>"こしょう，ほーる"
                    ],
                    [
                        "name"=>"中華だし",
                        "description"=>"ちゅうかだし"
                    ],
                    [
                        "name"=>"鶏ガラだし",
                        "description"=>"とりがらだし"
                    ],
                    [
                        "name"=>"オイスターソース",
                        "description"=>"おいすたーそーす"
                    ],
                    [
                        "name"=>"豆板醤",
                        "description"=>"とうばんじゃん"
                    ],
                    [
                        "name"=>"甜麵醬",
                        "description"=>"てんめんじゃん"
                    ],
                    [
                        "name"=>"コチュジャン",
                        "description"=>"こちゅじゃん"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'油',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"サラダ油",
                        "description"=>"さらだあぶら"
                    ],
                    [
                        "name"=>"ごま油",
                        "description"=>"ごまあぶら"
                    ],
                    [
                        "name"=>"オリーブオイル",
                        "description"=>"おりーぶおいる"
                    ],
                    [
                        "name"=>"ヒマワリ油",
                        "description"=>"ひまわりあぶら"
                    ],
                    [
                        "name"=>"アマニオイル",
                        "description"=>"あまにおいる"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'お酒',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"日本酒",
                        "description"=>"にほんしゅ"
                    ],
                    [
                        "name"=>"ブランデー",
                        "description"=>"ぶらんでー"
                    ],
                    [
                        "name"=>"赤ワイン",
                        "description"=>"あかわいん"
                    ],
                    [
                        "name"=>"白ワイン",
                        "description"=>"しろわいん"
                    ],
                    [
                        "name"=>"キルシュ",
                        "description"=>"きるしゅ"
                    ],
                    [
                        "name"=>"グランマニエ",
                        "description"=>"ぐらんまるにえ"
                    ],
                    [
                        "name"=>"コアントロー",
                        "description"=>"こあんとろー"
                    ],
                    [
                        "name"=>"キュラソー",
                        "description"=>"きゅらそー"
                    ],
                ]
            ],
            [
                "category"=>[
                    'name'=>'製菓関連',
                    "description"=>""
                ],
                "items"=>[
                    [
                        "name"=>"ブラックチョコレート",
                        "description"=>"ぶらっくちょこれーと"
                    ],
                    [
                        "name"=>"スイートチョコレート",
                        "description"=>"すいーとちょこれーと"
                    ],
                    [
                        "name"=>"ホワイトチョコレート",
                        "description"=>"ほわいとちょこれーと"
                    ],
                    [
                        "name"=>"ゼラチン",
                        "description"=>"ぜらちん"
                    ],
                    [
                        "name"=>"アガー",
                        "description"=>"あがー"
                    ],
                    [
                        "name"=>"粉寒天",
                        "description"=>"こなかんてん"
                    ],
                    [
                        "name"=>"チョコチップ",
                        "description"=>"ちょこちっぷ"
                    ],
                    [
                        "name"=>"ラムレーズン",
                        "description"=>"らむれーずん"
                    ],
                    [
                        "name"=>"くるみ",
                        "description"=>""
                    ],
                    [
                        "name"=>"ベーキングパウダー",
                        "description"=>"べーきんぐぱうだー"
                    ],
                    [
                        "name"=>"粉糖",
                        "description"=>"ふんとう，こなざとう"
                    ],
                    [
                        "name"=>"グラニュー糖",
                        "description"=>"ぐらにゅーとう"
                    ],
                    [
                        "name"=>"溶けない粉砂糖",
                        "description"=>"とけないこなざとう"
                    ],
                    [
                        "name"=>"ココアパウダー",
                        "description"=>"ここあぱうだー"
                    ],
                    [
                        "name"=>"溶けないココアパウダー",
                        "description"=>"とけないここあぱうだー"
                    ],
                ]
            ],
        ];
        foreach($record as $row){
            $category=new Recipe_category();
            $category->CategoryId=Str::uuid();
            $category->name=$row['category']['name'];
            $category->description=$row['category']['description'];
            $category->AccountId=0;
            $category->status=1;
            if($category->save()){
                foreach($row['items'] as $row){
                    $item=new Recipe_item();
                    $item->ItemId=Str::uuid();
                    $item->CategoryId=$category['id'];
                    $item->name=$row['name'];
                    $item->description=$row['description'];
                    $item->unit=1;
                    $item->UnitPrice=0;
                    $item->AccountId=0;
                    $item->status=1;
                    $item->save();
                }
            }
        }
    }
}