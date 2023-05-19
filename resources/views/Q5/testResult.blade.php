<!DOCTYPE html>
<html>
 <head>
  <title>Q5 result</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js" type="text/javascript"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
 </head>
 <script>
 function scrollDown(clicked_id)
        {
          $('html').animate({scrollTop: $(window).scrollTop() + $(window).height()}, '500');    
        }

        function scrollUp(clicked_id)
        {
          $('html').animate({ scrollTop: 0 }, '500');
        }

        $( window ).on( "load", function() {
          let scrollHeight = Math.max(
  document.body.scrollHeight, document.documentElement.scrollHeight,
  document.body.offsetHeight, document.documentElement.offsetHeight,
  document.body.clientHeight, document.documentElement.clientHeight
);
                       console.log(scrollHeight);
        if (scrollHeight >1080)
        {
          console.log(document.getElementById("triangle-arrow-down"));
          document.getElementById("triangle-arrow-down").style.display  = "block";
          document.getElementById("triangle-arrow-up").style.display = "block";
          console.log(document.getElementById("triangle-arrow-down"));

        }
        });
    </script>
 <body>
 <a id = "triangle-arrow-down" class = "triangle-arrow-down" onclick = " scrollDown(this)"></a>
      <a id = "triangle-arrow-up" class = "triangle-arrow-up" onclick = " scrollUp(this)"></a>
   <div class = "user-information">
        <div class = "title-middle big-title"><ruby><rb>日</rb><rt>に</rt><rb>本</rb><rt>ほん</rt><rb>語</rb><rt>ご</rt>　</ruby>NATーTEST　５<ruby><rb>級</rb><rt>きゅう</rt></ruby></div>

        <div class = "title-middle big-title"><ruby><rb>今日</rb><rt>きょう</rt></ruby>の<ruby><rb>成</rb><rt>せい</rt><rb>績</rb><rt>せき</rt></ruby></div>
        <br />
        <br />

        <div class = "same-line">
            <div class = "left-side-result"><ruby><rb>受</rb><rt>じゅ</rt><rb>験</rb><rt>けん</rt><rb>番</rb><rt>ばん</rt><rb>号</rb><rt>ごう</rt></ruby></div>
            <div class = "right-side-result">{{$testeeInformation["id"]}}</div>
        </div>
        <br />

        <div class = "same-line">
            <div class = "left-side-result"><ruby><rb>名</rb><rt>な</rt><rb>前</rb><rt>まえ</rt></ruby></div>
            <div class = "right-side-result">{{$testeeInformation["name"]}}</div>
        </div>
        <br />

        <div class = "same-line">
            <div class = "left-side-result"><ruby><rb>試</rb><rt>し</rt><rb>験</rb><rt>けん</rt><rb>日</rb><rt>び</rt></ruby></div>
            <div class = "right-side-result">{{$testeeInformation["date"]}}</div>
        </div>
        <br />

        <div class = "same-line">
            <div class = "left-side-result"><ruby><rb>試</rb><rt>し</rt><rb>験</rb><rt>けん</rt><rb>会</rb><rt>かい</rt><rb>場</rb><rt>じょう</rt></ruby></div>
            <div class = "right-side-result">{{$testeeInformation["place"]}}</div>
        </div>
        <br />
        <div class = "table-diff same-line">
          <table id = "myTable">
            <tr>
              <td class = "first-column"><ruby><rb>第</rb><rt>だい</rt></ruby>１<ruby><rb>部</rb><rt>ぶ</rt><rb>門</rb><rt>もん</rt></ruby>・<ruby><rb>第</rb><rt>だい</rt></ruby>2<ruby><rb>部</rb><rt>ぶ</rt><rb>門</rb><rt>もん</rt><rb>小</rb><rt>しょう</rt><rb>計</rb><rt>けい</rt></ruby></td>
              <td class = "first-column">{{$informationTotal["s1s2Total"]}}</td>
              <td class = "first-column"><ruby><rb>点<rb/><rt>てん</rt></ruby></td>
              </th>
            </tr>
            <tr>
              <td class = "first-column"><ruby><rb>第</rb><rt>だい</rt></ruby>３<ruby><rb>部</rb><rt>ぶ</rt><rb>門</rb><rt>もん</rt></ruby></td>
              <td class = "first-column">{{ $informationTotal["s3Total"] }}</td>
              <td class = "first-column"><ruby><rb>点<rb/><rt>てん</rt></ruby></td>
            </tr>
            <tr>
              <td class = "first-column"><ruby><rb>合</rb><rt>ごう</rt><rb>計</rb><rt>けい</rt></ruby></td>
              <td class = "first-column">{{ $informationTotal["totalScore"] }}</td>
              <td class = "first-column"><ruby><rb>点<rb/><rt>てん</rt></ruby></td>
            </tr>
          </table>
          <div class = "table-diff">
              <div>★<ruby><rb>今日</rb><rt>きょう</rt></ruby>の<ruby><rb>成</rb><rt>せい</rt><rb>績</rb><rt>せき</rt><rb>評</rb><rt>ひょう</rt><rb>価</rb><rt>か</rt></ruby></div>
              <div class = "evaluation-result">{!! $informationTotal["messsage"] !!}</div>

          </div>
        </div>
        <div class = "table-diff">
          <table id = "questionSummaryTable" class = "same-line-chart question1">
            <tr>
              <th rowspan = "5" class = "title-middle"><ruby><rb>第</rb><rt>だい</rt><rb>一</rb><rt>いち</rt><rb>部</rb><rt>ぶ</rt><rb>門</rb><rt>もん</rt></ruby></th>
              <th class = "title-middle">もんだい</th>
              <th class = "title-middle">あなたの<ruby><rb>正</rb><rt>せい</rt><rb>答</rb><rt>とう</rt><rb>率</rb><rt>りつ</rt></ruby></th>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>漢</rb><rt>かん</rt><rb>字</rb><rt>じ</rt><rb>読</rb><rt>よ</rt>み</td>
              <td class = "score-column-style">{{ $query->s1_q1_rate}} %</td>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>表</rb><rt>ひょう</rt><rb>記</rb><rt>き</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s1_q2_rate}} %</td>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>文</rb><rt>ぶん</rt><rb>脈</rb><rt>みゃく</rt><rb>規</rb><rt>き</rt><rb>定</rb><rt>てい</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s1_q3_rate}} %</td>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>言</rb><rt>い</rt></ruby>い<ruby><rb>換</rb><rt>か</rt></ruby>え<ruby><rb>類</rb><rt>るい</rt><rb>義</rb><rt>ぎ</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s1_q4_rate}} %</td>
            </tr>
          </table>
          <div class = "contents same-line-chart">
            <canvas id="rader_result" width="400" height="400"></canvas>
          </div>
        </div>

        <div class = "table-diff">
          <table id = "questionSummaryTable" class = "same-line-chart question1">
            <tr>
            <th rowspan = "7" class = "title-middle"><ruby><rb>第</rb><rt>だい</rt><rb>ニ</rb><rt>に</rt><rb>部</rb><rt>ぶ</rt><rb>門</rb><rt>もん</rt></ruby></th>
              <th class = "title-middle">もんだい</th>
              <th class = "title-middle">あなたの<ruby><rb>正</rb><rt>せい</rt><rb>答</rb><rt>とう</rt><rb>率</rb><rt>りつ</rt></ruby></th>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>文</rb><rt>ぶん</rt></ruby>の<ruby><rb>文</rb><rt>ぶん</rt><rb>法</rb><rt>ぽう</rt></ruby>１</td>
              <td class = "score-column-style">{{ $query->s2_q1_rate}} %</td>

            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>文</rb><rt>ぶん</rt></ruby>の<ruby><rb>文</rb><rt>ぶん</rt><rb>法</rb><rt>ぽう</rt></ruby>２</td>
              <td class = "score-column-style">{{ $query->s2_q2_rate}} %</td>

            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>文</rb><rt>ぶん</rt><rb>章</rb><rt>しょう</rt></ruby>の<ruby><rb>文</rb><rt>ぶん</rt><rb>法</rb><rt>ぽう</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s2_q3_rate}} %</td>

            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>内</rb><rt>ない</rt><rb>容</rb><rt>よう</rt><rb>理</rb><rt>り</rt><rb>解</rb><rt>かい</rt></ruby>（<ruby><rb>短</rb><rt>たん</rt><rb>文</rb><rt>ぶん</rt></ruby>）</td>
              <td class = "score-column-style">{{ $query->s2_q4_rate}} %</td>

            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>内</rb><rt>ない</rt><rb>容</rb><rt>よう</rt><rb>理</rb><rt>り</rt><rb>解</rb><rt>かい</rt></ruby>（<ruby><rb>中</rb><rt>ちゅう</rt><rb>文</rb><rt>ぶん</rt></ruby>）</td>
              <td class = "score-column-style">{{ $query->s2_q5_rate}} %</td>

            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>情</rb><rt>じょう</rt><rb>報</rb><rt>ほう</rt><rb>検</rb><rt>けん</rt><rb>索</rb><rt>さく</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s2_q6_rate}} %</td>

            </tr>
            <!-- <tr>
              <td class = "border-hidden"></td>
              <td class = "border-hidden title-middle">合計</td>
              <td class = "score-column-style">{{$query->s2_q1_correct + $query->s2_q2_correct + $query->s2_q3_correct + $query->s2_q4_correct + $query->s2_q5_correct + $query->s2_q6_correct }}</td>
              <td class = "score-column-style">{{$query->s2_q1_perfect_score + $query->s2_q2_perfect_score + $query->s2_q3_perfect_score + $query->s2_q4_perfect_score + $query->s2_q5_perfect_score + $query->s2_q6_perfect_score}}</td>
            </tr> -->
          </table>
          <div class = "contents same-line-chart">
            <canvas id="rader_result_Q2" width="400" height="400"></canvas>
          </div>
        </div>


        <div class = "table-diff">
          <table id = "questionSummaryTable" class = "same-line-chart question1">
            <tr>
              <th rowspan = "5" class = "title-middle"><ruby><rb>第</rb><rt>だい</rt><rb>三</rb><rt>さん</rt><rb>部</rb><rt>ぶ</rt><rb>門</rb><rt>もん</rt></ruby></th>
              <th class = "title-middle">もんだい</th>
              <th class = "title-middle">あなたの<ruby><rb>正</rb><rt>せい</rt><rb>答</rb><rt>とう</rt><rb>率</rb><rt>りつ</rt></ruby></th>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>課</rb><rt>か</rt><rb>題</rb><rt>だい</rt><rb>理</rb><rt>り</rt><rb>解</rb><rt>かい</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s3_q1_rate}} %</td>
            </tr>
            <tr>
              <td class = "title-middle">ポイント<ruby><rb>理</rb><rt>り</rt><rb>解</rb><rt>かい</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s3_q2_rate}} %</td>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>発</rb><rt>はつ</rt><rb>話</rb><rt>わ</rt><rb>表</rb><rt>ひょう</rt><rb>現</rb><rt>げん</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s3_q3_rate}} %</td>
            </tr>
            <tr>
              <td class = "title-middle"><ruby><rb>即</rb><rt>そく</rt><rb>時</rb><rt>じ</rt><rb>応</rb><rt>おう</rt><rb>答</rb><rt>とう</rt></ruby></td>
              <td class = "score-column-style">{{ $query->s3_q4_rate}} %</td>
            </tr>
            <!-- <tr>
              <td class = "border-hidden"></td>
              <td class = "border-hidden title-middle">合計</td>
              <td class = "score-column-style">{{$query->s3_q1_correct + $query->s3_q2_correct + $query->s3_q3_correct + $query->s3_q4_correct}}</td>
              <td class = "score-column-style">{{$query->s3_q1_perfect_score + $query->s3_q2_perfect_score + $query->s3_q3_perfect_score + $query->s3_q4_perfect_score}}</td>
            </tr> -->
          </table>
          <div class = "contents same-line-chart  more-padding">
            <canvas id="rader_result_Q3" width="400" height="400"></canvas>
          </div>
        </div>
        <script type="text/javascript">
                var data = {
                labels: ["漢字読み", "表記", "文脈規定", "言い換え類義"], 
                datasets: [　 
                    {　
                        label: "Sec 1",　
                        backgroundColor: "rgba(220, 220, 220, 0)",　　
                        borderColor: "rgba(255,99,132,1)",　　
                        pointBackgroundColor: "rgba(179,181,198,1)",　
                        pointBorderColor: "#fff", 
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(179,181,198,1)",　
                        data: [{{ $query->s1_q1_rate}},{{ $query->s1_q2_rate}}, {{ $query->s1_q3_rate}}, {{ $query->s1_q4_rate}}]　
                    }
                ]
            };

            var ctx = $("#rader_result");

            var myRadarChart = new Chart(ctx, {
                type: 'radar',
                data: data, 
                options: {　
                    scale: {
                      ticks: {
                        beginAtZero: true,　
                        max: 100,
                        stepSize:50,
                      },
                      pointLabels:{
                        fontSize: 15,
                      },
                    },
                    legend: {
                      display: false
                    },
                    tooltips: {
                      callbacks: {
                        label: function(tooltipItem) {
                        return tooltipItem.yLabel;
                       }
                    }
                  }
                }
            });

            var dataQuestion2 = {
                labels: ["文の文法１", "文の文法２", "文章の文法", "内容理解（短文）","内容理解（中文）","情報検索"], 
                datasets: [　 
                    {　
                        label: "Sec 2",　
                        backgroundColor: "rgba(220, 220, 220, 0)",　　
                        borderColor: "rgba(255,99,132,1)",　　
                        pointBackgroundColor: "rgba(179,181,198,1)",　
                        pointBorderColor: "#fff", 
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(179,181,198,1)",　
                        data: [{{ $query->s2_q1_rate}},{{ $query->s2_q2_rate}}, {{ $query->s2_q3_rate}}, {{ $query->s2_q4_rate}}, {{ $query->s2_q5_rate}}, {{ $query->s2_q6_rate}}]                    }
                ]
            };
            var ctxQuestion2 = $("#rader_result_Q2");

            var myRadarChart = new Chart(ctxQuestion2, {
                type: 'radar',
                data: dataQuestion2, 
                options: {　
                    scale: {
                      ticks: {
                        beginAtZero: true,　
                        max: 100
                      },
                      pointLabels:{
                        fontSize: 15,
                      },
                    },
                    legend: {
                      display: false
                    },
                    tooltips: {
                      callbacks: {
                        label: function(tooltipItem,data) {
                        return tooltipItem.yLabel;
                       }
                    }
                  }
                }
            });

            var dataQuestion3 = {
                labels: ["課題理解", "ポイント理解", "発話表現", "即時応答"], 
                datasets: [　 
                    {　
                        label: "Sec 3",　
                        backgroundColor: "rgba(220, 220, 220, 0)",　　
                        borderColor: "rgba(255,99,132,1)",　　
                        pointBackgroundColor: "rgba(179,181,198,1)",　
                        pointBorderColor: "#fff", 
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(179,181,198,1)",　
                        data: [{{ $query->s3_q1_rate}},{{ $query->s3_q2_rate}}, {{ $query->s3_q3_rate}}, {{ $query->s3_q4_rate}}]　
                    }
                ]
            };
            var ctxQuestion3 = $("#rader_result_Q3");
            Chart.defaults.global.defaultFontSize = 10;
        
            var myRadarChart = new Chart(ctxQuestion3, {
                type: 'radar',
                data: dataQuestion3, 
                options: {　
                    scale: {
                      ticks: {
                        beginAtZero: true,　
                        max: 100
                      },
                      pointLabels:{
                        fontSize: 15,
                      },
                    },
                    legend: {
                      display: false
                    },
                    tooltips: {
                      callbacks: {
                        label: function(tooltipItem) {
                        return tooltipItem.yLabel;
                       }
                    }
                  }   
                }
            });
            function printAndNextPage() {
              window.print()
              //window.location = "/ScoreDetailOption";
              window.location = "/End5Level";
            }
    </script>
    </div>
    <div id = "printPageButton" class = "print-button-style"><input type="button" class="btn btn-primary" value="PRINT" onClick=" printAndNextPage()"/>
    </div>
 </body>
</html>