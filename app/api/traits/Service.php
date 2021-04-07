<?php

declare(strict_types=1);

namespace app\api\traits;

trait Service
{
    use service\NormalList;
    use service\TreeList;
    use service\Add;
    use service\Save;
    use service\Read;
    use service\Update;
    use service\Delete;
    use service\Restore;
    use service\Misc;
}
