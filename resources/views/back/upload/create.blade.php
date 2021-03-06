@extends('layout.layer')

@section('head_style'){{-- layer 表单样式 --}}
	<style type="text/css">
		.close_button{
			margin-bottom: 25px;
		}
		.yascmf_layer{
			width: 560px;
			margin: 0 20px;
		}
		/*覆写部分样式,以便适合弹窗*/
		.yascmf_layer .validation_tips_area{
			width: 500px;
		}
		.yascmf_layer ul li label.description,{
			line-height: 20px !important;
		}
		.yascmf_layer ul li .form_item {
			line-height: 20px !important;
			margin: 5px 0;
		}
		.yascmf_layer p.tips_text{
			color: #083;
			font-size: 12px;
			line-height: 20px;
			margin: 5px 0;
		}
		.avgrund-close {
			display: block;
			color: #555;
			font-size: 14px;
			text-decoration: none;
			text-transform: uppercase;
			position: absolute;
			top: 6px;
			right: 10px;
		}
	</style>
@stop

@section('mainLayerCon')
@parent
	<div class="validation_tips_area" style="display: none;">
		<div class="tips_text">
			<p class="be_happy"><i class="fa fa-smile-o"></i>  上传文件成功！</p>
			<p class="text_info text_small">本提示栏2秒之后自动关闭！</p>
		</div>
	</div>

{!! Form::open( array('url' => route('admin.upload.store'), 'method' => 'post', 'id' => 'uploadPictureForm', 'files'=>true) ) !!}
	<div class="upload_picture_form">{{-- 表单数据项目 --}}
		<ul>
			<li>
				<label class="description" for="name">上传图片  <span class="required">(*)</span></label>
				<div class="form_item">
					{!! Form::file('picture') !!}
				</div>
			</li>
			
		</ul>
		<div class="form_buttons">
			<input class="flat_btn yas_green" type="submit" id="uploadPicSubmit" value="上传">
		</div>
	</div>
{!! Form::close() !!}
@stop

@section('endLayerJS')
<script src="{{ asset('assets/lib/layer/layer.min.js') }}"></script>{{-- 加载layer插件 --}}
<script src="{{ asset('assets/lib/form/jquery.form.js') }}"></script>{{-- 加载jquery.form插件 --}}
<script type="text/javascript">

	//比如在iframe中关闭自身
	var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引
	$('.avgrund-close').on('click', function(){
		parent.layer.close(index); //执行关闭
	});

	@include('scripts.endBuildHtml')

	// ajax form拦截提交事件
	$('#uploadPicSubmit').click(function(){
		var options = {
			dataType: 'json',
			timeout: 3000,
			success: function (data) {
				var html = build_html(data.status,data.info,data.operation);
				$('.validation_tips_area').html(html).css('display','block');
				setTimeout( function(){$('.validation_tips_area').fadeOut('slow');},2000);
				if(data.status === 1)  //成功
				{
					var from = '{{ Input::get('from','thumb') }}';
					parent.$('#'+ from).val(data.info);  //回调图片地址到父窗口
					parent.layer.close(index);
					//setTimeout("parent.location.reload()",1000);
				}
				else
				{
					setTimeout("location.reload()",1000);
				}
			},
			error: function(){
				var html = build_html(0, '失败：服务器端异常', '操作');
				$('.validation_tips_area').html(html).css('display','block');
				setTimeout( function(){$('.validation_tips_area').fadeOut('slow');},2000);
				//setTimeout("location.reload()",1000);
			}
		};
		$('#uploadPictureForm').ajaxForm(options);
	});
</script>
@stop


