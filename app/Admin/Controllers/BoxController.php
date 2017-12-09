<?php

namespace App\Admin\Controllers;

use App\Models\Box;
use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\QrcodeExpoter;

class BoxController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('盒子');
            $content->description('列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('盒子');
            $content->description('编辑');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('盒子');
            $content->description('新增');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Box::class, function (Grid $grid) {
            $grid->filter(function($filter){
              $filter->equal('status')->select([''=> '全部', 1 => '待使用', 2 => '已使用']);
            });

            $grid->disableCreation();
            $grid->actions(function ($actions) {
              $actions->disableDelete();
              $actions->disableEdit();

              // append一个操作
              $route = route('download', ['box' => $actions->getKey()]);
              $actions->append('<a href="'.$route.'" title="下载二维码" alt="下载二维码" target="_blank"><i class="fa fa-download"></i></a>');
            });

            $grid->exporter(new QrcodeExpoter());

            // $grid->tools(function ($tools) {
            //     $tools->batch(function ($batch) {
            //         $batch->add('批量导出二维码', new ReleasePost(1));
            //     });
            // });


            $grid->id('ID')->sortable();
            $grid->user_id('用户昵称')->display(function ($user_id) {
              if (!$user_id) {
                return '';
              }
              $user = User::find($user_id);
              if ($user) {
                return $user->name;
              } else {
                return '';
              }
            });
            $grid->avatar('用户头像')->display(function () {
              if (!$this->user_id) {
                return '';
              }
              $user = User::find($this->user_id);
              if ($user) {
                return '<img src="'.$user->avatar.'" width="50" height="50"/>';
              } else {
                return '';
              }
            });
            $grid->status('状态')->display(function ($status) {
                if ($status == 1) {
                  return '待使用';
                } else {
                  return '已使用';
                }
            })->badge('green')->sortable();
            $grid->qrcode('二维码')->display(function ($qrcode) {
              if ($qrcode && !is_null($qrcode)) {
                return '<img src="'.\Storage::url($qrcode).'" width="50" height="50"/>';
              } else {
                return '';
              }
            });

            $grid->created_at('创建时间')->sortable();
            $grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Box::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('image');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
