<?php

namespace Database\Seeders;

use App\Models\ScaleQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScaleQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScaleQuestion::create([
            'question'=>'feeling nevous, anxiouso or edge?',
            'question_ar'=>'الشعور بالتوتر أو القلق أو الحافة؟',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Not being able to stop or control worrying',
            'question_ar'=>'عدم القدرة على توقيف أو التحكم في القلق',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'feeling nevous, anxiouso or edge?',
            'question_ar'=>'الشعور بالتوتر أو القلق',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Worrying too much about different things',
            'question_ar'=>'القلق كثيرا بشأن أشياء مختلفة',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Trouble relaxing',
            'question_ar'=>'مشاكل في الاسترخاء',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Being so resless that is hard to sit still',
            'question_ar'=>'لاتقدر و من الصعب أن تجلس ساكنًا',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Becoming easily annoyed or irritable',
            'question_ar'=>'الانزعاج أو الانزعاج بسهولة',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Feeling afraid,as if something awful might happen',
            'question_ar'=>'الشعور بالخوف وكأن شيئًا فظيعًا قد يحدث',
            'scale_id'=>1
        ]);
        ScaleQuestion::create([
            'question'=>'Thought that you would be better off dead or off hurting yourself in some way',
            'question_ar'=>'اعتقدت أنك ستكون أفضل حالًا ميتًا أو تؤذي نفسك بطريقة ما',
            'scale_id'=>1
        ]);

        //depression
        ScaleQuestion::create([
            'question'=>'Little interest or pleasure of doing things',
            'question_ar'=>'القليل من الاهتمام أو متعة القيام بالأشياء',
            'scale_id'=>2
        ]);
        ScaleQuestion::create([
            'question'=>'Feeling down, depressed or hopless',
            'question_ar'=>'الشعور بالاكتئاب أو اليأس',
            'scale_id'=>2
        ]);
        ScaleQuestion::create([
            'question'=>'Trouble falling or staying asleep, or sleeping too much',
            'question_ar'=>'صعوبة في النوم أو الاستمرار في النوم أو الإفراط في النوم',
            'scale_id'=>2
        ]);
        ScaleQuestion::create([
            'question'=>'Feeling tired or having little energy',
            'question_ar'=>' ',
            'scale_id'=>2
        ]);
        ScaleQuestion::create([
            'question'=>'Poor appetite or overeatinig',
            'question_ar'=>'شهية ضعيفة أومفرطة',
            'scale_id'=>2
        ]);
        ScaleQuestion::create([
            'question'=>'Feeling bad about yourself, or that you are a failure, or have let yourself or your family down',
            'question_ar'=>'الشعور بالسوء تجاه نفسك ، أو أنك فاشل ، أو خذل نفسك أو عائلتك',
            'scale_id'=>2
        ]);
        ScaleQuestion::create([
            'question'=>'Trouble concentrating on things, such as reading the newspaper or watching television',
            'question_ar'=>'صعوبة في التركيز على الأشياء ، مثل قراءة الجريدة أو مشاهدة التلفزيون',
            'scale_id'=>2
        ]);
    }
}
