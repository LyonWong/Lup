@servers(['local' => '127.0.0.1'])

@task('foo', ['on' => 'local'])
    echo "envoy deploy foo"
@endtask