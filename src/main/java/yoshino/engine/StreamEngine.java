package yoshino.engine;

import java.util.Map;

/**
 * Created by Volio on 2017/1/1.
 */
public interface StreamEngine {

    String getPublishUrl(String streamKey);

    Map<String, String> getPlayUrl(String streamKey);

    String getSnapshotPlayUrl(String streamKey);
}
