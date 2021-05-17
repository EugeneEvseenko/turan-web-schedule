<div class="row">
  <div class="col-lg-8">
    <div class="card-box shadow-sm text-center text-xl-left p-0 pt-2">
      <h4 class="header-title py-2 px-3 text-info">Расписание звонков</h4><hr>
      <div class="call-item inactive">
        <?foreach ($calls as $keylesson => $lesson):?>
        <div class="call <?=($lesson['active']) ? 'active' : 'inactive'?><?php if($lesson == end($calls)) echo " last-call";?>">
          <div class="row px-4<?=($lesson != end($calls)) ? ' pt-2' : ' py-2';?>">
            <div class="col-md-11 d-flex justify-content-center justify-content-md-start">
              <p class="h5 m-0">
                <strong>
                  С <?=date("G:i", strtotime($lesson['start']))?> до <?=date("G:i", strtotime($lesson['end']))?>
                </strong>
              </p>
            </div>

            <div class="col-md-1 align-self-center justify-content-center d-flex justify-content-md-end mt-2 m-md-0">
              <span class="badge badge-pill shadow-sm badge-<?=($lesson['active']) ?'light text-info':'info'?>">
                <?=$lesson['id']?> Пара
              </span>
            </div>
          </div>
          <?if(!is_null($lesson['pause-min']) || $lesson['active']):?>
          <div class="row py-2 px-4 pb-md-2">
            <div class="col-md align-self-center justify-content-center d-flex justify-content-md-start">
            <p class="font-13 mb-0">
              <?if($lesson['active']) {echo " До конца ".calc_diff($lesson['end']).". ";}?><?if(!is_null($lesson['pause-min'])):?>Перемена <?=$lesson['pause-min']?> минут.<?endif?>
            </p>
            </div>
          </div>
          <?endif?>
        </div>
        <?php if($lesson != end($calls)) echo "<hr>";?>
        <?endforeach?>
      </div>
    </div>

  </div>
  <?php include ('right-side.php');?>
</div>
